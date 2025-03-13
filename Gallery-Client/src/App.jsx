import { BrowserRouter, Routes, Route, Link } from 'react-router-dom';
import SignUp from "../pages/SignUp"
import Login from "../pages/Login"
import Gallery from '../pages/Gallery';
import './App.css'

function App() {

  return (
  
      <BrowserRouter>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/signup" element={<SignUp />} />
          <Route path="/gallery" element={<Gallery />} />
          {/* <Route path="/add-photo" element={<AddPhoto />} /> */}
          {/* <Route path="/edit-photo" element={<EditPhoto />} /> */}
        </Routes>
      </BrowserRouter>
    
  )
}

export default App
