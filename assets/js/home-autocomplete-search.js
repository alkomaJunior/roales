import autoComplete from "@tarekraafat/autocomplete.js";

const autoCompleteJS = new autoComplete({
    selector: "#home_search_city",
    placeHolder: "Where are you looking for?...",
    data: {
        src: async (query) => {
            query = "?page=1&country%5B%5D=United%20States&country%5B%5D=France";
            try {
                // Fetch Data from external Source
                const source = await fetch(`https://127.0.0.1:8000/api/locations${ query }`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                // Data should be an array of `Objects` or `Strings`
                const response = await source.json();
                const data = response["hydra:member"];
                return data;
            } catch (error) {
                return error;
            }
        },
        // Data source 'Object' key to be searched
        keys: ["city"],
        cache: false
    },
    resultsList: {
        element: (list, data) => {
            console.log(list, data);
            if (!data.results.length) {
                // Create "No Results" message element
                const message = document.createElement("div");
                // Add class to the created element
                message.setAttribute("class", "no_result");
                // Add message text content
                message.innerHTML = `<span>Found No Results for "${data.query}"</span>`;
                // Append message element to the results list
                list.prepend(message);
            }
        },
        noResults: true,
    },
    resultItem: {
        highlight: true
    },
    events: {
        input: {
            selection: (event) => {
                const selection = event.detail.selection.value;
                autoCompleteJS.input.value = selection.city;
            }
        }
    }
});