function populateOverlay(name, desc, git, linkedin, xing, facebook) {
    
	var overlay = document.getElementById("info");
  overlay.style.display = "block"; 
  overlay.innerHTML = "<div>"+name+"</div><div>"+desc+"</div><div>"+git+"</div><div>" +linkedin+" </div><div>"+xing+"</div><div>"+facebook+"</div>"; 
  
} 

function off() {
	var overlay= document.getElementById("info");
  overlay.style.display= "none";
} 
