const expDateBlock = document.getElementById("expDateDiv");

document.querySelector('.mopTypeSelect').addEventListener('change', (event) => {

    if(event.target['value']=='credit'){
        expDateBlock.style.display = "block";
    }else {
        expDateBlock.style.display = "none";
    }

});


// document.getElementById("button_MOPreset").addEventListener('click', (event) => {
//     creditCardBlock.style.display = "block";
//     bankAccountBlock.style.display = "none";
// });



