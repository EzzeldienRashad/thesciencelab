//add date to footer
document.getElementsByClassName("date")[0].innerHTML = 
  document.getElementsByClassName("current-date")[0].innerHTML == new Date().getFullYear() ? 
  "" : " - " + new Date().getFullYear();