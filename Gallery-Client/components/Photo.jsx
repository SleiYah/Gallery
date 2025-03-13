import React from 'react';
import { Link } from 'react-router-dom';
import '../styles/photo.css';

const Photo = ({ photo, onDelete }) => {
  const { id, title, tag, description, image } = photo;
  
  // Format image URL
  const imageUrl = `http://localhost:80/projects/Gallery/gallery-server/${image}`;
  
  return (
    <div className="photo-card">
      <div className="photo-image">
        <img src={imageUrl} alt={title} loading="lazy" />
      </div>
      <div className="photo-info">
        <h3 className="photo-title">{title}</h3>
        
        {tag && <span className="photo-tag">{tag}</span>}
        
        {description && (
          <p className="photo-description">
            {description.length > 100 
              ? `${description.substring(0, 100)}...` 
              : description}
          </p>
        )}
        
        <div className="photo-actions">
          <Link to={`/edit-photo/${id}`} className="edit-btn">Edit</Link>
          <button 
            onClick={() => onDelete(photo)} 
            className="delete-btn"
            aria-label={`Delete ${title}`}
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  );
};

export default Photo;