function handleClearAll() {
    const browseJob = document.getElementById("browseJob");
    if (!browseJob) return;


    const checkbox = document.querySelectorAll("input[type=checkbox]")
    const clearBtn = document.querySelector(".clearBtn");
    let noOfCheckboxChecked = 0;
    checkbox.forEach((el) => {
        el.addEventListener("click", () => {
            if (el.checked) {
                noOfCheckboxChecked++
                console.log(noOfCheckboxChecked)

                clearBtn.classList.remove("hidden")


            } else {
                noOfCheckboxChecked--;
                if (noOfCheckboxChecked == 0) {
                    clearBtn.classList.add("hidden")
                }
            }
        })
    })
}

handleClearAll()