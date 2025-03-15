import React, { useState, useEffect } from "react";
import { API_BASE_URL } from "../src/config";

import { Link, useNavigate } from "react-router-dom";
import axios from "axios";
import Photo from "../components/Photo";
import DeleteModal from "../components/DeleteModal";
import "../styles/gallery.css";

const Gallery = () => {
  const [photos, setPhotos] = useState([]);
  const [error, setError] = useState("");
  const [selectedTag, setSelectedTag] = useState("all");
  const [tags, setTags] = useState([]);
  const [deleteModal, setDeleteModal] = useState({
    isOpen: false,
    photo: null,
  });

  const navigate = useNavigate();

  const user = JSON.parse(localStorage.getItem("user_id"));

  useEffect(() => {
    if (!user) {
      navigate("/login");
      return;
    }

    fetchPhotos();
  }, [user, navigate]);

  const fetchPhotos = async () => {
    try {
      const response = await axios.post(`${API_BASE_URL}/getPhotos`, {
        user_id: user,
      });
      console.log("response", response);

      if (response.data.status === "success") {
        setPhotos(response.data.photos);

        const uniqueTags = response.data.photos.reduce((tags, photo) => {
          if (photo.tag && !tags.includes(photo.tag)) {
            tags.push(photo.tag);
          }
          return tags;
        }, []);

        setTags(uniqueTags);
      } else {
        setError("Failed to load photos");
      }
    } catch (err) {
      setError("Error connecting to server");
      console.error(err);
    }
  };

  const openDeleteModal = (photo) => {
    setDeleteModal({
      isOpen: true,
      photo: photo,
    });
  };

  const closeDeleteModal = () => {
    setDeleteModal({
      isOpen: false,
      photo: null,
    });
  };

  const handleDeletePhoto = async (photoId) => {
    try {
      const response = await axios.post(`${API_BASE_URL}/deletePhoto`, {
        user_id: user,
        photo_id: photoId,
      });

      if (response.data.status === "success") {
        setPhotos(photos.filter((photo) => photo.id !== photoId));
        closeDeleteModal();
      } else {
        setError("Failed to delete photo");
      }
    } catch (err) {
      setError("Error connecting to server");
      console.error(err);
    }
  };

  const handleLogout = () => {
    localStorage.removeItem("user_id");
    navigate("/login");
  };

  const filteredPhotos =
    selectedTag === "all"
      ? photos
      : photos.filter((photo) => photo.tag === selectedTag);

  return (
    <div className="gallery-container">
      <header className="gallery-header">
        <div className="header-content">
          <h1>My Cozy Gallery</h1>
          <div className="user-actions">
            <Link to="/add-photo" className="add-photo-btn">
              Add Photo
            </Link>
            <button onClick={handleLogout} className="logout-btn">
              Logout
            </button>
          </div>
        </div>
        {tags.length > 0 && (
          <div className="tag-filter">
            <span className="filter-label">Filter by tag:</span>
            <button
              className={`tag-btn ${selectedTag === "all" ? "active" : ""}`}
              onClick={() => setSelectedTag("all")}
            >
              All
            </button>
            {tags.map((tag) => (
              <button
                key={tag}
                className={`tag-btn ${selectedTag === tag ? "active" : ""}`}
                onClick={() => setSelectedTag(tag)}
              >
                {tag}
              </button>
            ))}
          </div>
        )}
      </header>

      {error && <div className="error-message">{error}</div>}

      {filteredPhotos.length === 0 ? (
        <div className="empty-gallery">
          <p>Your gallery is empty. Add some photos to get started!</p>
          <Link to="/add-photo" className="add-photo-btn">
            Add Your First Photo
          </Link>
        </div>
      ) : (
        <div className="photos-grid">
          {filteredPhotos.map((photo) => (
            <Photo
              key={photo.id}
              photo={photo}
              onDelete={() => openDeleteModal(photo)}
            />
          ))}
        </div>
      )}

      {deleteModal.isOpen && deleteModal.photo && (
        <DeleteModal
          photo={deleteModal.photo}
          onClose={closeDeleteModal}
          onConfirm={handleDeletePhoto}
        />
      )}
    </div>
  );
};

export default Gallery;
