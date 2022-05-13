/*Gestion du Logo + blog articles*/

//http://localhost/wordpress_site/wp-json/wp/v2/posts // modif du fichier functions // page_logo_src

var xhr = new XMLHttpRequest();
xhr.open('GET', 'http://localhost/wordpress_site/wp-json/wp/v2/posts?_embed', true);

// If specified, responseType must be empty string or "text"
xhr.responseType = 'json';

xhr.onload = function () {
    if (xhr.readyState === xhr.DONE) {
        if (xhr.status === 200) {

            var myData = xhr.response;
            var logo = xhr.response[0].page_logo_src;
            for (var i = 0; i < myData.length; i++) {

                // let x = "<div class='box-content h-32 w-32 p-4 border-4'>" + myData[i].slug + "</div>"

                let content = myData[i].excerpt.rendered.replace(/(<([^>]+)>)/ig, "");

                let x = '<div class="w-full md:w-1/3 p-6 flex flex-col flex-grow flex-shrink">'
                    + '<div class="flex-1 bg-white rounded-t rounded-b-none overflow-hidden shadow-lg">'
                    + '<a href="#" class="flex flex-wrap no-underline hover:no-underline">'
                    + '<img src="' + myData[i]["_embedded"]["wp:featuredmedia"][0]["source_url"] + '"'
                    + 'class="h-64 w-full rounded-t pb-6">'
                    + '<div class ="w-full font-bold text-xl text-gray-900 px-6">' + myData[i].title.rendered
                    + '</div>'
                    + '<p class ="text-gray-800 font-serif text-base px-6 mb-5">'
                    + content
                    + '</p>'
                    + '</a>'
                    + '</div>'
                    + '<div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow-lg p-6">'
                    + '<a class="bg-white hover:bg-gray-100 text-gray-600  py-2 px-2 border border-gray-400 rounded shadow"'
                    + 'href = "post_details.php?id=' + myData[i].id + '" >LIRE LA SUITE</a >'
                    + '<br/><br/>'
                    + '<p class ="w-full text-gray-600 text-xs md:text-sm px-6">' + myData[i].date + '</p>'
                    + '</div>'
                    + '</div>';

                document.getElementById("myContent").innerHTML += x;

            }

            document.getElementById("logo").src = logo;

        }
    }
};

xhr.send(null);