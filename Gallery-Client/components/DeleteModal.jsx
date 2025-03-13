import React from 'react';
import '../styles/deleteModal.css';

const DeleteModal = ({ photo, onClose, onConfirm }) => {
  return (
    <div className="modal-overlay">
      <div className="modal-container">
        <div className="modal-header">
          <h3>Delete Photo</h3>
          <button className="close-button" onClick={onClose}>×</button>
        </div>
        
        <div className="modal-content">
          <div className="delete-preview">
            <img 
              src={`http://localhost:80/projects/Gallery/gallery-server/${photo.image}`} 
              alt={photo.title} 
            />
          </div>
          
          <p className="delete-message">
            Are you sure you want to delete <strong>{photo.title}</strong>?
          </p>
          <p className="delete-warning">
            This action cannot be undone.
          </p>
        </div>
        
        <div className="modal-actions">
          <button 
            className="cancel-button" 
            onClick={onClose}
          >
            Cancel
          </button>
          <button 
            className="delete-button" 
            onClick={() => onConfirm(photo.id)}
          >
            Delete Photo
          </button>
        </div>
      </div>
    </div>
  );
}

export default DeleteModal;