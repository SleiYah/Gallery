import React, { useState, useEffect } from "react";
import { API_BASE_URL } from "../src/config";

import { Link, useNavigate } from "react-router-dom";
import axios from "axios";
import "../styles/addPhoto.css";

const AddPhoto = () => {
  const [formData, setFormData] = useState({
    title: "",
    tag: "",
    description: "",
  });
  const [selectedImage, setSelectedImage] = useState(null);
  const [base64Image, setBase64Image] = useState("");
  const [error, setError] = useState("");

  const navigate = useNavigate();

  const user = JSON.parse(localStorage.getItem("user_id"));

  useEffect(() => {
    if (!user) {
      navigate("/login");
      return;
    }
  }, [user, navigate]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });
  };

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setSelectedImage(file);

      const reader = new FileReader();
      reader.onloadend = () => {
        setBase64Image(reader.result);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!formData.title.trim()) {
      setError("Please enter a title for your photo");
      return;
    }

    if (!selectedImage) {
      setError("Please select an image to upload");
      return;
    }
    if (!formData.tag.trim()) {
      setError("Please enter a tag for your photo");
      return;
    }
    if (!formData.description.trim()) {
      setError("Please enter a description for your photo");
      return;
    }

    try {
      const response = await axios.post(`${API_BASE_URL}/addPhoto`, {
        user_id: user,
        title: formData.title,
        tag: formData.tag,
        description: formData.description,
        image: base64Image,
      });

      if (response.data.status === "success") {
        navigate("/gallery");
      } else {
        setError(response.data.message || "Failed to upload photo");
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
          <h1>Add New Photo</h1>
          <p>Add a special moment to your cozy gallery</p>
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
            <label htmlFor="tag">Tag (optional)</label>
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
            <label htmlFor="description">Description (optional)</label>
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
            <label htmlFor="image">Photo</label>
            <input
              type="file"
              id="image"
              name="image"
              onChange={handleImageChange}
              accept="image/*"
              className="file-input"
            />

            {base64Image && (
              <div className="image-preview">
                <img src={base64Image} alt="Preview" />
              </div>
            )}
          </div>

          <div className="form-actions">
            <Link to="/gallery" className="cancel-button">
              Cancel
            </Link>
            <button type="submit" className="submit-button">
              Add Photo
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default AddPhoto;
