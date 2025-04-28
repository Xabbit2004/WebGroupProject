import "./Home.css";

function Home() {
  return (
    <div>
      <h1>Library of Babble</h1>
      <br></br>
      <p className = "intro-blurb">
        Welcome to our Library of Babble! This is our Web Technologies final group project.
        Our website is a library management website, you can check out the books we have available, and check one out.
        If one is not available then you may place it on hold to be checked out later.
        Books may only be checked out if you are a registered user of this website, and the same 
        goes for holds!
      </p>
      <br></br>
      <br></br>
      <br></br>
      <table className = "bookTable">
        <thhead>
          <tr>
            <th>Title</th>
            <th>Availibility</th>
          </tr>
        </thhead>
        <tbody>
          <tr></tr>
        </tbody>
      </table>
    </div>
  );
};

export default Home;
