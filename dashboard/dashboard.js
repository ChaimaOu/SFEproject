document.addEventListener("DOMContentLoaded", () => {
    // Mobile sidebar toggle
    const toggleSidebar = () => {
      const sidebar = document.querySelector(".sidebar")
      sidebar.classList.toggle("active")
    }
  
    // Add mobile menu button functionality
    const menuToggle = document.querySelector(".menu-toggle")
    if (menuToggle) {
      menuToggle.addEventListener("click", toggleSidebar)
    }
  
    // Notification dropdown
    const notificationIcon = document.querySelector(".notifications")
    if (notificationIcon) {
      notificationIcon.addEventListener("click", () => {
        // Removed alert
        console.log("Notifications clicked")
      })
    }
  
    // User dropdown
    const userAvatar = document.querySelector(".user-avatar")
    if (userAvatar) {
      userAvatar.addEventListener("click", () => {
        // Removed alert
        console.log("User profile clicked")
      })
    }
  
    // Sidebar navigation - COMPLETELY REWRITTEN to allow navigation
    // No more alerts or preventDefault() that block navigation
    const navItems = document.querySelectorAll(".sidebar-nav a")
    navItems.forEach((item) => {
      // We're not adding any click handlers that would interfere with navigation
      // Just adding the active class when clicked for visual feedback
      if (item.getAttribute("href") !== "#") {
        // Only for links that have real destinations
        item.addEventListener("click", function () {
          // Remove active class from all items
          navItems.forEach((navItem) => {
            navItem.parentElement.classList.remove("active")
          })
  
          // Add active class to clicked item
          this.parentElement.classList.add("active")
  
          // Let the default navigation happen naturally
        })
      }
    })
  
    // Quick action buttons - MODIFIED to not show alerts
    const actionCards = document.querySelectorAll(".action-card")
    actionCards.forEach((card) => {
      card.addEventListener("click", function (e) {
        if (this.getAttribute("href") === "#") {
          e.preventDefault()
          // Removed alert
          console.log("Action clicked:", this.querySelector("span").textContent)
        }
        // Otherwise let the navigation happen
      })
    })
  
    // Responsive chart resizing
    window.addEventListener("resize", () => {
      if (typeof ApexCharts !== "undefined") {
        try {
          ApexCharts.exec("mainChart", "render")
          ApexCharts.exec("courseDistributionChart", "render")
          ApexCharts.exec("partnerTypesChart", "render")
        } catch (error) {
          console.error("Error rendering charts:", error)
        }
      }
    })
  })
  