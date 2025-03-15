
import { BrowserRouter, Routes, Route, Link, Navigate} from 'react-router-dom';
import SignUp from "../pages/SignUp"
import Login from "../pages/Login"
import AddPhoto from "../pages/AddPhoto"
import EditPhoto from "../pages/EditPhoto"

import Gallery from '../pages/Gallery';
import './App.css'

function App() {

  return (
  
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Navigate to="/login"/>}/>
          <Route path="/login" element={<Login />} />
          <Route path="/signup" element={<SignUp />} />
          <Route path="/gallery" element={<Gallery />} />
          <Route path="/add-photo" element={<AddPhoto />} />
          <Route path="/edit-photo/:id" element={<EditPhoto />} />
        </Routes>
      </BrowserRouter>
    
  )
}

export default App
