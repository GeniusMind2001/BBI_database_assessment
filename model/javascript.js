function fetch_data() {
    fetch("../../model/webService.php?page=getAllposts")
    .then(data => data.json())
    .then(data => {
        console.log(data);
        localStorage.setItem("posts", JSON.stringify(data));
    });
}

function display_data() {
    let posts = localStorage.getItem("posts");
    if(posts !== null) {
        let post_list = document.getElementById("post_name");
        let vote_list = document.getElementById("post_vote");
        let user_firstname = document.getElementById("user_firstname");
        let user_lastname = document.getElementById("user_lastname");
        let publish_date = document.getElementById("post_vote");
        
        posts = JSON.parse(posts);
        //post name
        for (let post of posts){
            let list_item = document.createElement("h1");
            list_item.innerText = post.order_name;
            post_list.appendChild(list_item);
        }
        //post publish date
        for (let post of posts){
            let post_date = document.createElement("p");
            post_date.innerText = post.publish_date;
            publish_date.appendChild(post_date);
        }
        //user firstname
        for (let post of posts){
            let post_firstname = document.createElement("p");
            post_firstname.innerText = post.firstname;
            user_firstname.appendChild(post_firstname);
        }
        //user_lastname
        for (let post of posts){
            let post_lastname = document.createElement("p");
            post_lastname.innerText = post.lastname;
            user_lastname.appendChild(post_lastname);
        }
        // post votes
        for (let post of posts){
            let vote_item = document.createElement("p");
            vote_item.innerText = post.vote_num;
            vote_list.appendChild(vote_item);
        }
    }
}