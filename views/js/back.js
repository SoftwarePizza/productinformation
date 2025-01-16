
document.addEventListener("DOMContentLoaded", function(event) {
  document.getElementById("name").addEventListener("change", handleFeatureNameChange);
  handleFeatureNameChange();
console.log(window.location.hostname);
});

function handleFeatureNameChange() {
  var e = document.getElementById("name");
  var value = e.options[e.selectedIndex].value;
  

  fetch('https://' + window.location.hostname + '/module/productinformation/ajax?name=' + value)
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          var options = "";
          for (var i = 0; i < data.selected.length; i++) {
           options = options + "<option value = \"" + data.selected[i].value + "\">" + data.selected[i].value + "</option>";
         }
         document.getElementById("value").innerHTML = options;
         document.getElementById("value").disabled = false;
        });
}

