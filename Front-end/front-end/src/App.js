import { BrowserRouter, Routes, Route } from "react-router-dom";
import Home from "./pages/Home";
import Navbar from "./components/Navbar";
import Loginpage from "./pages/Loginpage";

function App() {
  return (
   <BrowserRouter>
   <Navbar />
   <Routes>
    <Route path = "/" element={<Home />} />
    <Route path = "/Loginpage" element={<Loginpage />} />
    <Route path = "*" element = {<h1>404- Page Not Found</h1>} />

  </Routes>
  </BrowserRouter>

  );
}

export default App;
