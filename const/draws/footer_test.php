<style>
footer .container{
    margin-top: -50px;
}
</style>
<script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.10.0/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.10.0/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyD9dnt0wJnDV5q7V_lxCeq5lGNd5Y49SP8",
    authDomain: "buscophylo.firebaseapp.com",
    projectId: "buscophylo",
    storageBucket: "buscophylo.appspot.com",
    messagingSenderId: "714021229973",
    appId: "1:714021229973:web:f3a300a70bcc876305929c",
    measurementId: "G-T34476LHCY"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script>
<style>
          #inratitle{
              color: white;
          }
          
          #inratitle:hover{
              color: orange;
          }
          .footer:after {
  content: '';
  position: absolute;
  top: 0;
  height: 300px;
  left: 0;
  right: 0;
  display: block;
  z-index: 1;
  background: linear-gradient(180deg, #000022 0%, #000022 100%);
}

@media only screen and (min-width: 1200px) {
    .mainfooter {
        float: left;
    }
    #logodiv {
        width: 50%; margin-left: 5%;
    }
    .footer__flixtv {
        width: 100%; margin-left: 23%;
    }
}
</style>
<footer class="footer">
  <div class="container">
    <div class="col-12">
        <div class="footer__flixtv">
            
                <div class="mainfooter">
          <center><img src="/img/logo.png" style="height: 60px;" /></center>
          </div>
          <div id="logodiv">
              <center>
          <h6 class="footer__title" style="font-size: 12px; color: white;"><a href="https://www.inra.org.ma/" id="inratitle">National Institute of Agricultural Research (INRA), CRRA-Rabat<br></a>Biotechnology Research Unit<br>Av. Mohamed Belarbi Alaoui BP. 6356, Rabat-Instituts 10101, Rabat, Morocco</h6>
          </center>
          </div>
        </div>
        </div>
    </div>
  </div>
</footer>
