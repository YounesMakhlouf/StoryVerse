import '../styles/admin.scss';
import '../bootstrap';
import'../img/logo.webp';
import {Chart} from "chart.js";

 function CreateGenderChart(url){
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
 }

function CreateLangChart(url){
    fetch(url).then(response => response.json())
        .then(
            data=> {
                console.log(data);
                const labels=data.language;
                const counts=data.count;
                const ctx = document.getElementById('language_chart').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: counts,

                        backgroundColor:[
                            'rgb(255, 99, 132)',
                                'rgb(255, 159, 64)',
                                'rgb(255, 205, 86)',
                                'rgb(75, 192, 192)',
                                'rgb(54, 162, 235)',
                                'rgb(153, 102, 255)',
                                'rgb(201, 203, 207)']
            }]
            }
            });
})        .catch(error => console.error(error));
}


CreateGenderChart('/chart-data');
CreateLangChart('/language')

// fetch(url).then(response => response.json())
//     .then(
//         data=> {
//             console.log(data);})
//

