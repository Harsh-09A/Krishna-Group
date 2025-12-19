AOS.init();

document.addEventListener("DOMContentLoaded", function () {
    // Select all 3 forms
    const forms = ["enquiry_form1", "enquiry_form2", "enquiry_form3"];
    const loader = document.getElementById("loader");
  
    forms.forEach(formId => {
      const form = document.getElementById(formId);
      if (form) {
        form.addEventListener("submit", function (e) {
      
          e.preventDefault(); // Stop default form submit
          loader.style.display = "flex";
          // Get form data
          const formData = new FormData(form);
  
          // Send data to Google Apps Script
          fetch(form.action, {
            method: "POST",
            body: formData
          })
          .then(response => {
            if (response.ok) {
              form.reset(); 
              // Redirect to Thank You page
              window.location.href = "thank_you.html"; 
            } else {
              loader.style.display = "none"; // hide if error
              alert("Something went wrong. Please try again.");
            }
          })
          .catch(error => {
            loader.style.display = "none"; // hide if error
            console.error("Error!", error);
            alert("There was an error submitting the form.");
          });
        });
      }
    });
  });