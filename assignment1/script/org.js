
window.onload = function(){
	
	navn();
}

function comment(){
 	
 	var parent = document.getElementById("donations");

	var div_recent = document.createElement("div");
	var div_text = document.createElement("div");

	div_recent.setAttribute("class","recent_donation");
	div_text.setAttribute("class","recent_text");

	var h4 = document.createElement("h4");
	var p = document.createElement("p");

	h4.innerHTML = generateName();
	p.innerHTML = "Just donated $" + randomAmount() + "!";
	
	div_recent.classList.add("hide");

	div_text.appendChild(h4);
	div_text.appendChild(p);
	div_recent.appendChild(div_text);
	parent.prepend(div_recent);

	setTimeout(function(){
		div_recent.classList.remove("hide")
	}, 50);


}

function navn(){
	setTimeout(function(){
		comment()
		navn()
	}, Math.floor(Math.random()*5000));
}

function generateName(){
	var first = ["Mohamed", "Salma","Imane","Zineb","Anas","Hamza","Ayoub","Omar","Saïd","Karim","Ali","Ahmed","Rim","Kawtar","Fatima","Hajar","Ghita","Nada",
		"Hiba","Rita","Maha","Marwa","Fati","Ilyass","Ismail","Khalid","Brahim","Inès","Chaimae","Zineb","Hajar","Rania","Mounia","Jalal","Saad","Alae",
        "Salah","Faiza","Mounia","Ilham","Niama","Dounia","Safae"];
	var last  = ["Abad","Awan","Ansari","Asad","Beyr","Baccus","Begum","Gaber","Galla","Ghani","Hadi","Haider","Hafeez","Hakeem","Emami","Edris","Eid",
		"Fahmy","Farag","Fares","Farhat","Farooq","Hussain","Imam","Ishmael","Jabara","DeHut","Kamara","Kassem","Karim","Kazi","Khalaf","Khan",
        "Majid","Raad","Omar","Rafiq","Mussa","Rasheed","Rauf","Munir"];

	var rFirst = first[Math.floor((Math.random()*first.length)+0)];
	var rLast = last[Math.floor((Math.random()*first.length)+0)];

	return rFirst + " " + rLast;
}
function randomAmount(){
	var cash = Math.floor((Math.random()*10)+1);
	return cash;

}