import '../styles/admin.scss';
import '../bootstrap';
import'../img/logo.webp';
import {Chart} from "chart.js";
 let url='/chart-data';
 fetch(url).then(response => response.json())
     .then(
        data=> {
            console.log(data);
          const labels=data.labels;
          const counts=data.datasets.data;
         const ctx = document.getElementById('gender_chart').getContext('2d');
         const chart = new Chart(ctx, {
          type: 'pie',
          data: {
           labels: labels,
           datasets: [{
            data: counts,
            backgroundColor:data.datasets.backgroundColor
           }]
          }
         });
        })
     .catch(error => console.error(error));





