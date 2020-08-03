const creditCardBlock = document.getElementById("creditInfo");
const bankAccountBlock = document.getElementById("bankAccountInfo");

document.querySelector('.paymentMethod').addEventListener('change', (event) => {

    if(creditCardBlock.style.display == "block"){
        creditCardBlock.style.display = "none";
        bankAccountBlock.style.display = "block";
    }else {
        creditCardBlock.style.display = "block";
        bankAccountBlock.style.display = "none";
    }

});


document.getElementById("button_MOPreset").addEventListener('click', (event) => {
    console.log("heh")
    creditCardBlock.style.display = "block";
    bankAccountBlock.style.display = "none";

});
