


function toggleSubMenu(subMenuId) {
    var submenu = document.getElementById(subMenuId);
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
}



document.addEventListener('DOMContentLoaded', function () {
  var countdownInterval = setInterval(function () {
      var currentDate = new Date().getTime();
      var remainingTime = targetDate - currentDate;

      if (remainingTime <= 0) {
          clearInterval(countdownInterval);
          document.getElementById("countdown").innerHTML = "Countdown expired!";
      } else {
          var days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
          var hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

          document.getElementById("countdown").innerHTML = days + "J " + hours + "H " + minutes + "M " + seconds + "S";
      }
  }, 1000);
});


document.addEventListener("DOMContentLoaded", function () {

  var data = {
      labels: ["Victoire  Exterieur", "Nul","Victoire Domicile",],
      datasets: [{
          data: [ defaite, nul,victoire],
          backgroundColor: [ "#f80404", "#5f6460","#03e734"]
      }]
  };

  var ctx = document.getElementById("myPieChart").getContext('2d');

  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: data ,
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false,
        }}}
  });
});

document.getElementById("burger").addEventListener("click", function() {
  var sidebar = document.getElementById("sidebar");
  if (sidebar.style.width === "150px") {
    sidebar.style.width = "0";
  } else {
    sidebar.style.width = "150px";
  }
});
