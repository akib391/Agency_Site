// Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
  // Initialize any components that need JavaScript
  initNavbarScroll();
  initFormValidation();
  initServiceFilters();
});

// Change navbar background on scroll
function initNavbarScroll() {
  const navbar = document.querySelector(".navbar");
  if (navbar) {
    // Set initial state of navbar
    setNavbarState();

    // Update navbar on scroll
    window.addEventListener("scroll", function () {
      setNavbarState();
    });
  }
}

// Function to set navbar state based on scroll position
function setNavbarState() {
  const navbar = document.querySelector(".navbar");
  const hasHero = document.querySelector(".hero") !== null;

  if (window.scrollY > 100) {
    // When scrolled down, always use dark background
    navbar.classList.add("bg-dark", "shadow");
    navbar.classList.remove("bg-transparent");
  } else {
    // At the top of the page
    if (hasHero) {
      // Only use transparent background if hero section exists
      navbar.classList.add("bg-transparent");
      navbar.classList.remove("bg-dark", "shadow");
    } else {
      // For pages without hero, use dark background even at the top
      navbar.classList.add("bg-dark");
      navbar.classList.remove("bg-transparent");
    }
  }
}

// Form validation for contact and buy service forms
function initFormValidation() {
  const forms = document.querySelectorAll(".needs-validation");

  if (forms.length) {
    Array.from(forms).forEach((form) => {
      form.addEventListener(
        "submit",
        (event) => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add("was-validated");
        },
        false
      );
    });
  }
}

// Service filtering functionality
function initServiceFilters() {
  const filterButtons = document.querySelectorAll(".filter-btn");
  const serviceItems = document.querySelectorAll(".service-card");

  if (filterButtons.length && serviceItems.length) {
    filterButtons.forEach((button) => {
      button.addEventListener("click", function () {
        // Remove active class from all buttons
        filterButtons.forEach((btn) => {
          btn.classList.remove("active");
        });

        // Add active class to clicked button
        this.classList.add("active");

        const filter = this.getAttribute("data-filter");

        // Show/hide services based on filter
        serviceItems.forEach((item) => {
          if (filter === "all") {
            item.style.display = "block";
          } else {
            if (item.classList.contains(filter)) {
              item.style.display = "block";
            } else {
              item.style.display = "none";
            }
          }
        });
      });
    });
  }
}

// Display selected service price in the buy service form
function updateServicePrice(selectElement) {
  const selectedOption = selectElement.options[selectElement.selectedIndex];
  const priceElement = document.getElementById("selected-price");

  if (priceElement && selectedOption) {
    const price = selectedOption.getAttribute("data-price");
    if (price) {
      priceElement.textContent = "$" + price;
      document.getElementById("service_price").value = price;
    } else {
      priceElement.textContent = "$0";
      document.getElementById("service_price").value = "0";
    }
  }
}

// Toggle password visibility in login form
function togglePasswordVisibility() {
  const passwordInput = document.getElementById("password");
  const toggleIcon = document.getElementById("togglePassword");

  if (passwordInput && toggleIcon) {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleIcon.setAttribute("data-lucide", "eye-off");
    } else {
      passwordInput.type = "password";
      toggleIcon.setAttribute("data-lucide", "eye");
    }
    lucide.createIcons();
  }
}

// Confirm delete action
function confirmDelete(id, type) {
  if (confirm(`Are you sure you want to delete this ${type}?`)) {
    document.getElementById(`delete-form-${id}`).submit();
  }
}
