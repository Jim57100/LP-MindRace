/**
 * Autocomplete
 */


/**
 * Get text from the list
 * @param {*} event 
 * @param {integer} id 
 */
 function get_text(event, id) {

  let string = event.textContent;

  const input = document.querySelectorAll('.playername');
  input[id].value = string;
  console.log(input[id].value);

  const search_result = document.querySelectorAll('.search_result');
  search_result[id].innerHTML = '';

}

/**
 * Ajax request to display users in a list
 * @param {*} username 
 * @param {integer} id 
 */
function load_data(username, id) {

  if (username.length > 1) {
    // console.log('ok');
    let form_data = new FormData();

    form_data.append('username', username);

    let ajax_request = new XMLHttpRequest();

    ajax_request.open('POST', '/invitation');
    ajax_request.send(form_data);

    ajax_request.onreadystatechange = function () {
      if (ajax_request.readyState == 4 && ajax_request.status == 200) {

        let response = JSON.parse(ajax_request.responseText);

        let html = '<div class="list-group">';

        if (response.length > 0) {
          for (let count = 0; count < response.length; count++) {
            html += '<a href="#" class="list-group-item list-group-item-action" onclick="get_text(this, ' + id + ')">' + response[count].username + '</a>';
          }
        } else {
          html += '<a href="#" class="list-group-item list-group-item-action disabled">No Data Found</a>';
        }

        html += '</div>';

        const search_result = document.querySelectorAll('.search_result');
        search_result[id].innerHTML = html;
      }
    }
  } else {
    const search_result = document.querySelectorAll('.search_result');
    search_result[id].innerHTML = '';
  }

}


/**
 * PLAYERS COLORS FEATURE
 */


/**
 * Targetting the event
 * @param {*} e 
 * @returns 
 */
function getEventTarget(e) {
  e = e || window.event;
  return e.target || e.srcElement;
}


/**
 * 
 * @param {*} block 
 * @param {*} tab_couleur 
 * @param {*} i 
 */
function verifTarget(block, tab_couleur, i) {
  var target = getEventTarget(event);
  if (target.id != tab_couleur[0][1] && target.id != tab_couleur[1][1] && target.id != tab_couleur[2][1] && target.id != tab_couleur[3][1] && target.id != tab_couleur[4][1] && target.id != tab_couleur[5][1] ) {

  // alert(target.id);
  var img = document.createElement("img");

  if (target.id == 'black') {
    // document.getElementById('color1').value = "black";
    img.src = "./images/tortleblack.png";
    tab_couleur[i][1] = 'black';
  } else if (target.id == 'blue') {
    img.src = "./images/tortleBlue.png";
    tab_couleur[i][1] = 'blue';
  } else if (target.id == 'green') {
    img.src = "./images/tortleGreen.png";
    tab_couleur[i][1] = 'green';
  } else if (target.id == 'orange') {
    img.src = "./images/tortleOrange.png";
    tab_couleur[i][1] = 'orange';
  } else if (target.id == 'pink') {
    img.src = "./images/tortlePink.png";
    tab_couleur[i][1] = 'pink';
  } else if (target.id == 'purple') {
    img.src = "./images/tortlePurple.png";
    tab_couleur[i][1] = 'purple';
  } else if (target.id == 'red') {
    img.src = "./images/tortleRed.png";
    tab_couleur[i][1] = 'red';
  } else if (target.id == 'turquoise') {
    img.src = "./images/tortleTurquoise.png";
    tab_couleur[i][1] = 'turquoise';
  } else if (target.id == 'white') {
    img.src = "./images/tortleWhite.png";
    tab_couleur[i][1] = 'white';
  } else if (target.id == 'yellow') {
    img.src = "./images/tortleYellow.png";
    tab_couleur[i][1] = 'yellow';
  }


  img.style.height = '30';
  block.appendChild(img);
  console.log(tab_couleur);
} 
else alert("Couleur déjà selectionnée, modifier celle de l'autre joueur ou choisissez-en une autre");
}

function test_img() {

  const ul = document.getElementById('selectTortle0'); // Parent
  const ul2 = document.getElementById('selectTortle1');
  const ul3 = document.getElementById('selectTortle2');
  const ul4 = document.getElementById('selectTortle3');
  const ul5 = document.getElementById('selectTortle4');
  const ul6 = document.getElementById('selectTortle5');

  let tab_couleur = [
    ["j1", ""],
    ["j2", ""],
    ["j3", ""],
    ["j4", ""],
    ["j5", ""],
    ["j6", ""],
  ];

  ul.addEventListener('click', (event) => {
    let target = getEventTarget(event);
    console.log(target.id);
    
      let block = document.getElementById("x1");
      if ((tab_couleur[0][1]) !== "") {
        document.getElementById('x1').innerHTML = "";
        tab_couleur[0][1] = "";
      }
      verifTarget(block, tab_couleur, 0);
      document.getElementById('color1').value = tab_couleur[0][1];

  })

  ul2.addEventListener('click', (event) => {
    let block = document.getElementById("x2");
    if ((tab_couleur[1][1]) !== "") {
      document.getElementById('x2').innerHTML = "";
      tab_couleur[1][1] = "";
    }
    verifTarget(block, tab_couleur, 1);
    document.getElementById('color2').value = tab_couleur[1][1];

  })

  ul3.addEventListener('click', (event) => {
    let block = document.getElementById("x3");
    if ((tab_couleur[2][1]) !== "") {
      document.getElementById('x3').innerHTML = "";
      tab_couleur[2][1] = "";
    }
    verifTarget(block, tab_couleur, 2);
    document.getElementById('color3').value = tab_couleur[2][1];
  })


  ul4.addEventListener('click', (event) => {
    let block = document.getElementById("x4");
    if ((tab_couleur[3][1]) !== "") {
      document.getElementById('x4').innerHTML = "";
      tab_couleur[3][1] = "";
    }
    verifTarget(block, tab_couleur, 3);
    document.getElementById('color4').value = tab_couleur[3][1];
  })


  ul5.addEventListener('click', (event) => {
    let block = document.getElementById("x5");
    if ((tab_couleur[4][1]) !== "") {
      document.getElementById('x5').innerHTML = "";
      tab_couleur[4][1] = "";
    }
    verifTarget(block, tab_couleur, 4);
    document.getElementById('color5').value = tab_couleur[4][1];
  })


  ul6.addEventListener('click', (event) => {
    let block = document.getElementById("x6");
    if ((tab_couleur[5][1]) !== "") {
      document.getElementById('x6').innerHTML = "";
      tab_couleur[5][1] = "";
    }
    verifTarget(block, tab_couleur, 5);
    document.getElementById('color6').value = tab_couleur[5][1];
  })


}
test_img();

