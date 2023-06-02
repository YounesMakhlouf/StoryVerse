import '../styles/admin.scss';
import '../bootstrap';
import Chart from 'chart.js/auto';

let genderColors = ["#89CFF0", "#f4c2c2"]
let languageColors = [
    'rgb(255, 99, 132)',
    'rgb(255, 159, 64)',
    'rgb(255, 205, 86)',
    'rgb(75, 192, 192)',
    'rgb(54, 162, 235)',
    'rgb(153, 102, 255)',
    'rgb(201, 203, 207)']
let genresColors = [
    '#FF8C8C',
    '#FEE440',
    '#146C94',
    '#FFBF9B',
    '#54B435',
    '#B2A4FF'
]

function fetchData(url) {
    return fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch chart data');
            }
            return response.json();
        })
        .catch(error => {
            console.error('An error occurred:', error);
        });
}

function createChart(ctx, data, colors, type, legendVisibility) {
    return new Chart(ctx, {
        type: type,
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: '',
                    data: data.data,
                    backgroundColor: colors
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    display: legendVisibility
                }
            }
        }
    });
}

function initializeChart(url, id, colors, type, legendVisibility) {
    const ctx = document.getElementById(id).getContext('2d');
    fetchData(url)
        .then(data => {
            createChart(ctx, data, colors, type, legendVisibility);
        });
}

initializeChart('/chart-data', 'gender_chart', genderColors, 'pie', true);
initializeChart('/language', 'language_chart', languageColors, 'bar', false);
initializeChart('/genre', 'genre_chart', genresColors, 'bar', false);