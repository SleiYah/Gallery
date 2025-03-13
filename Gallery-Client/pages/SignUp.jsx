import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import '../styles/signup.css'; 

const SignUp = () => {
  const [formData, setFormData] = useState({
    email: '',
    password: '',
    confirmPassword: ''
  });
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prevData => ({
      ...prevData,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Simple validation
    if (!formData.email || !formData.password || !formData.confirmPassword) {
      setError('Please fill in all fields');
      return;
    }
    
    if (formData.password !== formData.confirmPassword) {
      setError('Passwords do not match');
      return;
    }
    
    setError('');
    
    try {
      // Replace with your actual API endpoint
      const response = await axios.post('http://localhost:80/projects/Gallery/gallery-server/register', {
        email: formData.email,
        password: formData.password
      });
      
      const data = response.data;
      
      if (data.status === 'success') {
        setSuccess(true);
      } else {
        setError(data.message || 'Something went wrong');
      }
    } catch (err) {
      if (err.response) {
        setError(err.response.data.message || `Error: ${err.response.status}`);
      } else if (err.request) {
        setError('No response from server. Please try again.');
      } else {
        setError('Request error. Please try again.');
      }
    } 
  };

  return (
    <div className="signup-container">
      <div className="signup-card">
        <div className="signup-header">
          <h1>Create Your Gallery</h1>
          <p>Your colorful space for precious memories</p>
        </div>
        
        {success ? (
          <div className="success-message">
            <h3>Welcome aboard! 🎉</h3>
            <p>Your personal gallery is ready. Time to share your beautiful moments with the world.</p>
            <Link to="/login" className="login-link">Login to continue</Link>
          </div>
        ) : (
          <form onSubmit={handleSubmit} className="signup-form">
            {error && <div className="error-message">{error}</div>}
            
            <div className="form-group">
              <label htmlFor="email">Email</label>
              <input
                type="email"
                id="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                placeholder="your.email@example.com"
                required
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="password">Password</label>
              <input
                type="password"
                id="password"
                name="password"
                value={formData.password}
                onChange={handleChange}
                placeholder="Create a secure password"
                required
              />
            </div>
            
            <div className="form-group">
              <label htmlFor="confirmPassword">Confirm Password</label>
              <input
                type="password"
                id="confirmPassword"
                name="confirmPassword"
                value={formData.confirmPassword}
                onChange={handleChange}
                placeholder="Confirm your password"
                required
              />
            </div>
            
            <button 
              type="submit" 
              className="signup-button"
            >
Sign Up            </button>
            
            <div className="login-prompt">
              Already have a gallery? <Link to="/login" className="login-link">Log in</Link>
            </div>
          </form>
        )}
      </div>
      
  
    </div>
  );
};

export default SignUp;