import AOS from "aos";
import "aos/dist/aos.css";
import "../styles/app.scss";
import "./components/notifications";
import "./components/navigation";

require('bootstrap');
import "../bootstrap";

window.addEventListener("load", () => {
    AOS.init({
        duration: 1000, easing: "ease-in-out", once: true, mirror: false,
    });
});