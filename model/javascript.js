function fetch_details() {
    fetch("../../model/webService.php?page=getUserdetails")
        .then(data => data.json())
        .then(data => {
            localStorage.setItem("userDetails", JSON.stringify(data));
        });
}
function fetch_posts() {
    fetch("../../model/webService.php?page=getAllposts")
        .then(data => data.json())
        .then(data => {
            localStorage.setItem("posts", JSON.stringify(data));
        });
}
function fetch_orders() {
    fetch("../../model/webService.php?page=getAllOrders")
        .then(data => data.json())
        .then(data => {
            localStorage.setItem("orders", JSON.stringify(data));
        });
}
function fetch_discounts() {
    fetch("../../model/webService.php?page=getAlldiscounts")
        .then(data => data.json())
        .then(data => {
            localStorage.setItem("discounts", JSON.stringify(data));
        });
}
function fetch_items() {
    fetch("../../model/webService.php?page=getAllitems")
        .then(data => data.json())
        .then(data => {
            localStorage.setItem("items", JSON.stringify(data));
        });
}
/* theme settings */
const toggleBtn = document.querySelector('#toggle-theme');
toggleBtn.addEventListener('click', e => {
    if (document.documentElement.hasAttribute('theme')) {
        document.documentElement.removeAttribute('theme');
        localStorage.setItem("theme", "white");
    } else {
        document.documentElement.setAttribute("theme", "dark");
        localStorage.setItem("theme", "dark");
    }
});

function set_theme() {
    if (localStorage.getItem("theme") === "white") {
        document.documentElement.setAttribute('theme', 'white');
    } else {
        document.documentElement.setAttribute('theme', 'dark');
        localStorage.setItem("theme", "dark");
    }
}


function includeAccount() {
    let details = localStorage.getItem("userDetails");
    if (details !== null) {
        let firstname_list = document.getElementById("users_firstname");
        let lastname_list = document.getElementById("users_lastname");
        let email_list = document.getElementById("users_emailaddress");
        let discount_list = document.getElementById("assigned_discounts");
        let order_list = document.getElementById("assigned_orders");
    
        firstnames = JSON.parse(details);
        lastnames = JSON.parse(details);
        emails = JSON.parse(details);
        discounts = JSON.parse(details);
        orders = JSON.parse(details);
    
        for (let firstname of firstnames) {
            let firstname_item = document.createElement("p");
            firstname_item.innerText = firstname.firstname;
            firstname_list.appendChild(firstname_item);
        }
        for (let lastname of lastnames) {
            let lastname_item = document.createElement("p");
            lastname_item.innerText = lastname.lastname;
            lastname_list.appendChild(lastname_item);
        }
        for (let email of emails) {
            let email_item = document.createElement("p");
            email_item.innerText = email.email;
            email_list.appendChild(email_item);
        }
        for (let discount of discounts) {
            let discount_item = document.createElement("p");
            discount_item.innerText = discount.discount_code;
            discount_list.appendChild(discount_item);
        }
        for (let order of orders) {
            let order_item = document.createElement("p");
            order_item.innerText = order.order_name;
            order_list.appendChild(order_item);
        }
    }
}

function includePosts() {
    let posts = localStorage.getItem("posts");

    if (posts !== null) {
        let date_list = document.getElementById("publish_date");
        let vote_list = document.getElementById("post_vote");
        let name_list = document.getElementById("post_name");
        let firstname_list = document.getElementById("user_firstname");
        let lastname_list = document.getElementById("user_lastname");
    
        votes = JSON.parse(posts);
        dates = JSON.parse(posts);
        names = JSON.parse(posts);
        firstnames = JSON.parse(posts);
        lastnames = JSON.parse(posts);
    
        for (let date of dates) {
            let date_item = document.createElement("p");
            date_item.innerText = date.publish_date;
            date_item.classList.add("pub-date");
            date_list.appendChild(date_item);
        }
        for (let vote of votes) {
            let vote_item = document.createElement("p");
            vote_item.innerText = vote.vote_num;
            vote_item.classList.add("vote-num");
            vote_list.appendChild(vote_item);
        }
        for (let name of names) {
            let name_item = document.createElement("p");
            name_item.innerText = name.order_name;
            name_item.classList.add("post-names");
            name_list.appendChild(name_item);
        }
        for (let firstname of firstnames) {
            let firstname_item = document.createElement("p");
            firstname_item.innerText = firstname.firstname;
            firstname_item.classList.add("first-names");
            firstname_list.appendChild(firstname_item);
        }
        for (let lastname of lastnames) {
            let lastname_item = document.createElement("p");
            lastname_item.innerText = lastname.lastname;
            lastname_item.classList.add("last-names");
            lastname_list.appendChild(lastname_item);
        }
    }
}

function run_js() {
    fetch_details();
    fetch_posts();
    fetch_orders();
    fetch_discounts();
    fetch_items();
    set_theme();
}

var menuItems = document.getElementsByClassName("item");
for (var loop = 0; loop < menuItems.length; loop++) {
    menuItems[loop].addEventListener('click', function (evt) { clickMenu(evt) });
}

function clickMenu(firedEvent) {
    var eventID = firedEvent.target.id;
    var eventIDArray = eventID.split('_');
    var newID = 'page_' + eventIDArray[1];
    var otherContentChildren = document.getElementById(newID).parentNode.children;
    for (loop = 0; loop < otherContentChildren.length; loop++) {
        otherContentChildren[loop].style.display = 'none';
    }
    document.getElementById(newID).style.display = 'block';
}
// makes order form show/hide and increase progress bar
function show_ordername() {
    document.getElementById('section_ordername').style.display = 'block';
    document.getElementById('progress_bar').style.width = '0%';
}
function show_icecreamform() {
    document.getElementById('section_ordername').style.display = 'none';
    document.getElementById('section_icecreamform').style.display = 'block';
    document.getElementById('progress_bar').style.width = '20%';
}
function show_toppingform() {
    document.getElementById('section_icecreamform').style.display = 'none';
    document.getElementById('section_toppingform').style.display = 'block';
    document.getElementById('progress_bar').style.width = '40%';
}
function show_sauceform() {
    document.getElementById('section_toppingform').style.display = 'none';
    document.getElementById('section_sauceform').style.display = 'block';
    document.getElementById('progress_bar').style.width = '60%';
}
function show_baseform() {
    document.getElementById('section_sauceform').style.display = 'none';
    document.getElementById('section_baseform').style.display = 'block';
    document.getElementById('progress_bar').style.width = '80%';
}
// POST functions

function login(){
    var fd = new FormData();
    fd.append("request", "loginUser")
    fd.append('email', document.getElementById("login_email").value);
    fd.append('password', document.getElementById("login_password").value);
    var url = '../../model/webService.php';
    fetch(url, {
        method: 'POST',
        body: fd,
        cache: 'no-cache',
        credentials: 'include'
    })

    .then(
        function (response) {
            if (response.status !== 200) {
                console.log("opps something went wrong.");
            }
            response.json().then(function (data) {
                if (data.name == -1) {
                    console.log("opps something wasn't right.");
                    // localstorage.setItem('authcode', null);
                } else {
                    // localStorage.setItem('authcode', JSON.stringify(data))
                }
            });
        }
    )
}

function registration() {
    var fd = new FormData();
    fd.append("request", "createUser")
    fd.append('firstname', document.getElementById("firstname").value);
    fd.append('lastname', document.getElementById("lastname").value);
    fd.append('email', document.getElementById("email").value);
    fd.append('password', document.getElementById("password").value);
    var url = '../../model/webService.php';
    fetch(url, {
        method: 'POST',
        body: fd,
        cache: 'no-cache',
        credentials: 'include'
    })
        .then(
            function (response) {
                if (response.status !== 200) {
                    console.log("opps something went wrong.");
                }
                response.json().then(function (data) {
                    if (data.name == -1) {
                        console.log("opps something wasn't right.");
                        // localstorage.setItem('authcode', null);
                    } else {
                        // localStorage.setItem('authcode', JSON.stringify(data))
                    }
                });
            }
        )
}

function editDetails() {
    var fd = new FormData();
    fd.append("request", "updateUserDetails")
    console.log("hello");
    fd.append('firstname', document.getElementById("edit_firstname").value);
    fd.append('lastname', document.getElementById("edit_lastname").value);
    fd.append('email', document.getElementById("edit_email").value);
    var url = '../../model/webService.php';
    fetch(url, {
        method: 'POST',
        body: fd,
        cache: 'no-cache',
        credentials: 'include'
    })
        .then(
            function (response) {
                if (response.status !== 200) {
                    console.log("opps");
                }
                response.json().then(function (data) {
                    if (data.name == -1) {
                        // localstorage.setItem('authcode', null);
                        console.log("something went wrong");
                    } else {
                        // localStorage.setItem('authcode', JSON.stringify(data))
                        console.log("two");
                    }
                });
            }
        )
}

function createOrdername() {
    var fd = new FormData();
    fd.append("request", "createOrder");
    fd.append('order_name', document.getElementById("order_name").value);
    fd.append('discount_code', document.getElementById("discount_code").value);
    fd.append('user_id', document.getElementById("user_id").value);
    
    var url = '../../model/webService.php';
    fetch(url, {
        method: 'POST',
        body: fd,
        cache: 'no-cache',
        credentials: 'include'
    })
        .then(
            function (response) {
                if (response.status !== 200) {
                    console.log("opps !== 200");
                }
                response.json().then(function (data) {
                    if (data.name == -1) {
                        // localstorage.setItem('getAllOrders', null);
                        console.log("something went wrong.");
                    } else {
                        // localStorage.setItem('getAllOrders', JSON.stringify(data));
                    }
                });
            }
        )
    // show_icecreamform();
}
/* 

   does not work yet, do not mark!

function createorderitems() {
    var fd = new FormData();
    fd.append("request", "OrderItems");
    fd.append('item_id', document.getElementsById("item_id").value);
    fd.append('order_id', document.getElementById("order_id").value);
    fd.append('quantity', document.getElementById("quantity").value);
    var url = '../../model/webService.php';
    fetch(url, {
        method: 'POST',
        body: fd,
        cache: 'no-cache',
        credentials: 'include'
    })
        .then(
            function (response) {
                if (response.status !== 200) {
                    console.log("opps");
                }
                response.json().then(function (data) {
                    if (data.name == -1) {
                        // localstorage.setItem('getAllOrders', null);
                        alert("Opps:something went terrible wrong");
                    } else {
                        // localStorage.setItem('getAllOrders', JSON.stringify(data));
                    }
                });
            }
        ) 
    item_id = document.getElementById("");
    if (item_id == "section_icecreamform") {
        show_toppingform();
    }
    if (item_id == "section_toppingform") {
        show_sauceform();
    }
    if (item_id == "section_sauceform") {
        show_baseform();
    }
    if (item_id == "section_baseform") {
        alert("Your order has been created!");
    }
} */


// sementic ui user-interaction code

$('#example2').progress({
    percent: 0
});

window.addEventListener("load", loader);

$('#toggle').click(function () {
    $('.ui.sidebar').sidebar('toggle');
});

function loader() {
    document.getElementById('spinner').removeAttribute('class', 'active');
    document.getElementById('spinner').setAttribute('class', 'disabled');
}