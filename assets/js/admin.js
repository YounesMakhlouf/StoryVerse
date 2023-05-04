import '../styles/admin.scss';
import '../bootstrap';
import'../img/logo.webp';
import {Chart} from "chart.js";
import {drawPointLegend} from "chart.js/helpers";

function CreateChart(url,id,colors,type,lengendVisibility){
    fetch(url).then(response => response.json())
        .then(
            data=> {
                console.log(data);
                const labels=data.labels;
                const counts=data.data;
                const ctx = document.getElementById(id).getContext('2d');
                const chart = new Chart(ctx, {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: '',
                            data: counts,
                            backgroundColor:colors
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: lengendVisibility
                            }
                        }
                    }

                });
            })
        .catch(error => console.error(error));
}



let genderColors=["#89CFF0", "#f4c2c2"]
let languageColors= [
    'rgb(255, 99, 132)',
    'rgb(255, 159, 64)',
    'rgb(255, 205, 86)',
    'rgb(75, 192, 192)',
    'rgb(54, 162, 235)',
    'rgb(153, 102, 255)',
    'rgb(201, 203, 207)']
let genresColors=[
    '#FF8C8C',
    '#FEE440',
    '#146C94',
    '#FFBF9B',
    '#54B435',
    '#B2A4FF'
]

CreateChart('/chart-data','gender_chart',genderColors,'pie',true);
CreateChart('/language','language_chart',languageColors,'bar',false);
CreateChart('/genre','genre_chart',genresColors,'bar',false);

