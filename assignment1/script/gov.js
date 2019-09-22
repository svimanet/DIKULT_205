
window.onload = function(){
	
	navn();
}

function comment(){
 	
 	var parent = document.getElementById("comments_section");

	var div_comment = document.createElement("div");
	div_comment.setAttribute("class","comment");
	div_comment.setAttribute("style", "background:" + randomBG() + ";");

	var div_titlepic = document.createElement("div");
	div_titlepic.setAttribute("class", "pic_title");

	var img = document.createElement("img");
	img.setAttribute("class", "comment_pic");
	img.setAttribute("src", "images/media/PNG/Circle%20Color/Twitter.png");

	var h3 = document.createElement("h3");
	var p = document.createElement("p");

	h3.innerHTML = generateName();
	p.innerHTML = randomMessage() + randomHash();
	div_comment.classList.add("hide");

	div_comment.appendChild(div_titlepic);
	div_titlepic.appendChild(img);
	div_titlepic.appendChild(h3);
	div_comment.appendChild(p);
	parent.prepend(div_comment);

	setTimeout(function(){
		div_comment.classList.remove("hide")
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

function randomMessage(){
	var message = [
	"Morocco is the greatest country. No other country si as good.",
	"Dont give in to the terrorists!!!",
	"Why do they want to ruin or great nation?",
	"I'm scared to go out at night :(",
	"Yesterday I was followed home from the farmers market. Thought it was terrorists but it was my shadow.",
	"I miss playing in playgrounds without being scared :'(",
	"My kids wont leave the house now, thanks sahrawi.. >:(",
	"ALLAH HATES SAHRAWI",
	"This is a serious issue in our contry. These organisations get sympathy and support from all over the world.",
	"I can't believe there is still a colony in Africa.. For the love of god, UJSARIO let go ._.",
	"I always think back to the good 'ol days when we could play with our children in peace :)",
	"How do people sympathize with these terrorists!? GOOGLE BEFORE YOU DONATE, KIDS",
	"Why can't we asll just get along? :)",
	"Never met a polite sahrawi man before .. and I meet a lot of people",
	"Came home to my kids hiding behind the sofa, they were playing 'moroccon and sahrawi'",
	"Put your faith in Allah and he will solve our problems! ",
	"Peopl from Morocco are the greatest. No other people are better.",
	"UJSARIO tried to recruit me once!! Dont fall for the deception!!",
	"Lies, lies and more lies from the sahrawi... :/",
	"Why cant they just be happy that we are trying to help them? >:(",
	"I wish i lived in a country where everyone are safe.. :'(",
	"Cant even get a wifi signal in western sahara, dont go there",
	"I used to travel a lot. I used to be able to see the world, now i cant even leave my house..",
	"I used to be an activist with a blog as well, but these fuckers are going too far.",
	"I get why they're mad, but srsly, cant they see we trying to help",
	"LIKE plz just love eachother...",
	"Peace and love, homies.",
	"I want peace so badly, but one does not simply force peace ..",
	"Who would have thought that writing a bunch of comments would be this hard?!",
	"Getting writers block just think of this nonsense...",
	"These blast marks are too accurate for tusken raiders, must be stormtroopers.",
	"The sahrawi are easily frightened but will return in greater numbers.",
	"Have you ever heard of darth plagius the wise? no? thought not."

	
	];

	return message[Math.floor((Math.random()*message.length)+0)];
}
function  randomHash() {
	var hashtag = [
		"SaveMorocco",
        "StandAgainsTerror",
        "UniteMorroco",
        "NoTerror",
        "WeWontLiveInFear"
    ]

	return "<br><p class='hash'>#" + hashtag[Math.floor((Math.random()*hashtag.length)+0)] + "</p>";

}
function randomBG(){
	var bgs = [
		"#CAEBF2",
		"#A6EBF2",
		"#CACEF2",
		"#CAEBC6",
		"#C7F3F2",
		"#82EBF2",
		"#9DCEF2",
		"#CACEF2",
		"#D4CFF2",
		"#ABF9C6",
		"#C1FFEF"
	]

	return bgs[Math.floor((Math.random()*bgs.length)+0)]
}