import React, { useState, useEffect } from "react";
import { API_BASE_URL } from "../src/config";

import { Link, useNavigate, useParams } from "react-router-dom";
import axios from "axios";
import "../styles/addPhoto.css";

const EditPhoto = () => {
  const [formData, setFormData] = useState({
    title: "",
    tag: "",
    description: "",
  });
  const [base64Image, setBase64Image] = useState("");
  const [preview, setPreview] = useState("");
  const [error, setError] = useState("");

  const navigate = useNavigate();
  const { id } = useParams();

  const user = JSON.parse(localStorage.getItem("user_id"));

  useEffect(() => {
    if (!user) {
      navigate("/login");
      return;
    }

    const fetchPhoto = async () => {
      try {
        const response = await axios.post(`${API_BASE_URL}/getPhoto`, {
          user_id: user,
          photo_id: id,
        });
        if (response.data.status === "success") {

          const photo = response.data.photo;

          setFormData({
            title: photo.title || "",
            tag: photo.tag || "",
            description: photo.description || "",
          });

          setPreview(`${API_BASE_URL}/${photo.image}`);
        } else {
          setError("Failed to load photo");
        }
      } catch (err) {
        console.error(err);
        setError("Error connecting to server");
      }
    };

    fetchPhoto();
  }, [user, id, navigate]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });
  };
  const changeImagetoBase64 = (file) => {
    const reader = new FileReader();
    reader.onloadend = () => {
      setPreview(reader.result);
      setBase64Image(reader.result);
    };
    reader.readAsDataURL(file);
  };
  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      changeImagetoBase64(file);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!formData.title.trim()) {
      setError("Please enter a title for your photo");
      return;
    }

    try {
      const requestData = {
        user_id: user,
        photo_id: id,
        title: formData.title,
        tag: formData.tag,
        description: formData.description,
      };

      const response = await axios.post(
        `${API_BASE_URL}/updatePhoto`,
        requestData
      );

      if (response.data.status === "success") {
        navigate("/gallery");
      } else {
        setError(response.data.message || "Failed to update photo");
      }
    } catch (err) {
      console.error(err);
      setError("Error connecting to server");
    }
  };

  return (
    <div className="form-container">
      <div className="form-card">
        <div className="form-header">
          <h1>Edit Photo</h1>
          <p>Update details of your special moment</p>
        </div>

        {error && <div className="error-message">{error}</div>}

        <form onSubmit={handleSubmit} className="photo-form">
          <div className="form-group">
            <label htmlFor="title">Title</label>
            <input
              type="text"
              id="title"
              name="title"
              value={formData.title}
              onChange={handleChange}
              placeholder="What's this photo called?"
            />
          </div>

          <div className="form-group">
            <label htmlFor="tag">Tag</label>
            <input
              type="text"
              id="tag"
              name="tag"
              value={formData.tag}
              onChange={handleChange}
              placeholder="E.g., nature, family, vacation"
            />
          </div>

          <div className="form-group">
            <label htmlFor="description">Description</label>
            <textarea
              id="description"
              name="description"
              value={formData.description}
              onChange={handleChange}
              placeholder="Tell the story behind this photo"
              rows="3"
            ></textarea>
          </div>

          <div className="form-group">
            <label htmlFor="image">
              Photo (leave empty to keep current image)
            </label>
            <input
              type="file"
              id="image"
              name="image"
              onChange={handleImageChange}
              accept="image/*"
              className="file-input"
            />

            {preview && (
              <div className="image-preview">
                <img src={preview} alt="Preview" />
              </div>
            )}
          </div>

          <div className="form-actions">
            <Link to="/gallery" className="cancel-button">
              Cancel
            </Link>
            <button type="submit" className="submit-button">
              Update Photo
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default EditPhoto;
