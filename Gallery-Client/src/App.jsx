import { BrowserRouter, Routes, Route, Link } from 'react-router-dom';
import SignUp from "../pages/SignUp"
import Login from "../pages/Login"

import './App.css'

function App() {

  return (
  
      <BrowserRouter>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/signup" element={<SignUp />} />
          {/* <Route path="/gallery" element={<Gallery />} /> */}
          {/* <Route path="/add-photo" element={<AddPhoto />} /> */}
        </Routes>
      </BrowserRouter>
    
  )
}

export default App
