
function searchById() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("jobID");
    filter = input.value.toUpperCase();
    table = document.getElementById("tableBody");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function searchByCategory() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("category").valueOf();
    filter = input.value.toUpperCase();
    table = document.getElementById("tableBody");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[4];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function getMoreJobInfo(jobID){
    jobdata=document.getElementById('data'+jobID).innerHTML;
    jobdata=JSON.parse(jobdata);

    //Write the long job description
    divElement = document.getElementById('longJobDescription');
    divElement.innerHTML='';
    divElement.innerHTML+='<h2>'+jobdata['title']+'</h2>';
    divElement.innerHTML+='<h4>'+jobdata['employerID']+'</h4>';
    divElement.innerHTML+='<p>Description: <br>'+jobdata['description']+'</p>';
    divElement.innerHTML+='<p>Requirements: <br>'+jobdata['requirements']+'</p>';
    divElement.innerHTML+='<div>Number of vacancies: '+jobdata['amountNeeded']+'</div>';
    divElement.innerHTML+='<br><div>Job posting closure date: '+jobdata['endingDate']+'</div>';

    /*This section is in to make sure there is only 1 expanded job at a time. TODO
    //Check if there are other button open as collapse
    var openButton=document.getElementById("tableBody").getElementsByClassName("collapsable")[0];
    if(!(typeof (openButton) == "undefined"))
    {
        openButtton.className='expandable';
        openButton.setAttribute( "onClick", "javascript: 'getMoreJobInfo(this.value)';" );
        openButton.innerHTML="<i class=\"material - icons\">expand_more</i>More"
    }
    */


    //Set the button as collapsable
    buttonElement = document.getElementById('button'+jobdata['jobID']);
    buttonElement.outerHTML=
        "<button class='collapsable' id ='button"+jobdata['jobID']+"' style='border: none' value='"+jobdata['jobID']+"' onclick='getMoreJobInfoCollapse(this.value)'><i class=\"material-icons\">expand_less</i>Less</button>"

    //Set the data in the form in case of application
    document.getElementById('appliedJob').value=jobdata['jobID'];

    //Make Apply button visible
    formElement = document.getElementById('jobApplyForm');
    formElement.style.visibility='visible';
}

function getMoreJobInfoCollapse(jobID){
    jobdata=document.getElementById('data'+jobID).innerHTML;
    jobdata=JSON.parse(jobdata);
    divElement = document.getElementById('longJobDescription');
    divElement.innerHTML='';

    //Set the button as expandable long job description
    buttonElement = document.getElementById('button'+jobdata['jobID']);
    buttonElement.outerHTML=
        "<button class='expandable' id ='button"+jobdata['jobID']+"' style='border: none' value='"+jobdata['jobID']+"' onclick='getMoreJobInfo(this.value)'><i class=\"material-icons\">expand_more</i>More</button>"

    //Remove the value of application form
    document.getElementById('appliedJob').value='';

    //Make Apply button visible
    formElement = document.getElementById('jobApplyForm');
    formElement.style.visibility='hidden';
}


