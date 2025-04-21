document.addEventListener("DOMContentLoaded", () => {
    // Sample student data for JavaScript functionality
    // In a real application, this would be fetched from the server
    const students = [
      {
        id: 1,
        photo: "https://randomuser.me/api/portraits/women/44.jpg",
        first_name: "Marie",
        last_name: "Dupont",
        email: "marie.dupont@example.com",
        phone: "+33 6 12 34 56 78",
        program: "Business Administration",
        enrollment_date: "2023-09-15",
        status: "Active",
        payment_status: "Paid",
        progress: 75,
      },
      {
        id: 2,
        photo: "https://randomuser.me/api/portraits/men/32.jpg",
        first_name: "Thomas",
        last_name: "Martin",
        email: "thomas.martin@example.com",
        phone: "+33 6 23 45 67 89",
        program: "Computer Science",
        enrollment_date: "2023-08-20",
        status: "Active",
        payment_status: "Partial",
        progress: 60,
      },
      // More students would be here in a real application
    ]
  
    // DOM Elements
    const addStudentBtn = document.getElementById("addStudentBtn")
    const studentModal = document.getElementById("studentModal")
    const closeModal = document.getElementById("closeModal")
    const cancelBtn = document.getElementById("cancelBtn")
    const studentForm = document.getElementById("studentForm")
    const modalTitle = document.getElementById("modalTitle")
    const studentId = document.getElementById("studentId")
    const viewStudentModal = document.getElementById("viewStudentModal")
    const closeViewModal = document.getElementById("closeViewModal")
    const editFromViewBtn = document.getElementById("editFromViewBtn")
    const deleteFromViewBtn = document.getElementById("deleteFromViewBtn")
    const deleteModal = document.getElementById("deleteModal")
    const closeDeleteModal = document.getElementById("closeDeleteModal")
    const cancelDeleteBtn = document.getElementById("cancelDeleteBtn")
    const confirmDeleteBtn = document.getElementById("confirmDeleteBtn")
    const progressInput = document.getElementById("progress")
    const progressValue = document.getElementById("progressValue")
  
    // View buttons
    const viewButtons = document.querySelectorAll(".view-btn")
    const editButtons = document.querySelectorAll(".edit-btn")
    const deleteButtons = document.querySelectorAll(".delete-btn")
  
    // Progress slider
    if (progressInput && progressValue) {
      progressInput.addEventListener("input", function () {
        progressValue.textContent = this.value
      })
    }
  
    // Open Add Student Modal
    if (addStudentBtn) {
      addStudentBtn.addEventListener("click", () => {
        modalTitle.textContent = "Add New Student"
        studentId.value = ""
        studentForm.reset()
        progressValue.textContent = "0"
        studentModal.style.display = "block"
      })
    }
  
    // Close Add/Edit Student Modal
    if (closeModal) {
      closeModal.addEventListener("click", () => {
        studentModal.style.display = "none"
      })
    }
  
    if (cancelBtn) {
      cancelBtn.addEventListener("click", () => {
        studentModal.style.display = "none"
      })
    }
  
    // Handle form submission
    if (studentForm) {
      studentForm.addEventListener("submit", (e) => {
        e.preventDefault()
  
        // In a real application, you would send this data to the server
        const formData = {
          id: studentId.value || Date.now(), // Use existing ID or generate a new one
          first_name: document.getElementById("firstName").value,
          last_name: document.getElementById("lastName").value,
          email: document.getElementById("email").value,
          phone: document.getElementById("phone").value,
          program: document.getElementById("program").value,
          enrollment_date: document.getElementById("enrollmentDate").value,
          status: document.getElementById("status").value,
          payment_status: document.getElementById("paymentStatus").value,
          progress: document.getElementById("progress").value,
          photo: "https://randomuser.me/api/portraits/lego/1.jpg", // Default photo for new students
        }
  
        // Show success message - MODIFIED to not show alert
        console.log(studentId.value ? "Student updated successfully!" : "Student added successfully!")
  
        // Close modal
        studentModal.style.display = "none"
  
        // In a real application, you would refresh the page or update the UI
        // For demo purposes, we'll just reload the page
        window.location.reload()
      })
    }
  
    // View Student
    viewButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const studentId = button.dataset.id
  
        // In a real application, you would fetch the student data from the server
        // For demo purposes, we'll use the sample data
        const student = findStudentById(studentId)
  
        if (student) {
          // Populate view modal
          document.getElementById("viewStudentPhoto").src = student.photo
          document.getElementById("viewStudentName").textContent = `${student.first_name} ${student.last_name}`
          document.getElementById("viewStudentProgram").textContent = student.program
  
          const statusBadge = document.getElementById("viewStudentStatus")
          statusBadge.textContent = student.status
          statusBadge.className = `status-badge ${student.status.toLowerCase()}`
  
          const paymentBadge = document.getElementById("viewStudentPayment")
          paymentBadge.textContent = student.payment_status
          paymentBadge.className = `payment-badge ${student.payment_status.toLowerCase()}`
  
          document.getElementById("viewStudentProgressBar").style.width = `${student.progress}%`
          document.getElementById("viewStudentProgress").textContent = `${student.progress}%`
  
          document.getElementById("viewStudentEmail").textContent = student.email
          document.getElementById("viewStudentPhone").textContent = student.phone
          document.getElementById("viewStudentEnrollment").textContent = formatDate(student.enrollment_date)
          document.getElementById("viewStudentId").textContent = `STU-${student.id.toString().padStart(5, "0")}`
  
          // Show view modal
          viewStudentModal.style.display = "block"
        }
      })
    })
  
    // Close View Student Modal
    if (closeViewModal) {
      closeViewModal.addEventListener("click", () => {
        viewStudentModal.style.display = "none"
      })
    }
  
    // Edit Student
    editButtons.forEach((button) => {
      button.addEventListener("click", () => {
        editStudent(button.dataset.id)
      })
    })
  
    // Edit from View Modal
    if (editFromViewBtn) {
      editFromViewBtn.addEventListener("click", () => {
        const studentIdText = document.getElementById("viewStudentId").textContent
        const id = studentIdText.replace("STU-", "").replace(/^0+/, "")
  
        viewStudentModal.style.display = "none"
        editStudent(id)
      })
    }
  
    function editStudent(id) {
      // In a real application, you would fetch the student data from the server
      // For demo purposes, we'll use the sample data
      const student = findStudentById(id)
  
      if (student) {
        // Populate form
        modalTitle.textContent = "Edit Student"
        studentId.value = student.id
        document.getElementById("firstName").value = student.first_name
        document.getElementById("lastName").value = student.last_name
        document.getElementById("email").value = student.email
        document.getElementById("phone").value = student.phone
        document.getElementById("program").value = student.program
        document.getElementById("enrollmentDate").value = student.enrollment_date
        document.getElementById("status").value = student.status
        document.getElementById("paymentStatus").value = student.payment_status
        document.getElementById("progress").value = student.progress
        progressValue.textContent = student.progress
  
        // Show modal
        studentModal.style.display = "block"
      }
    }
  
    // Delete Student
    deleteButtons.forEach((button) => {
      button.addEventListener("click", () => {
        showDeleteConfirmation(button.dataset.id)
      })
    })
  
    // Delete from View Modal
    if (deleteFromViewBtn) {
      deleteFromViewBtn.addEventListener("click", () => {
        const studentIdText = document.getElementById("viewStudentId").textContent
        const id = studentIdText.replace("STU-", "").replace(/^0+/, "")
  
        viewStudentModal.style.display = "none"
        showDeleteConfirmation(id)
      })
    }
  
    function showDeleteConfirmation(id) {
      // In a real application, you would fetch the student data from the server
      // For demo purposes, we'll use the sample data
      const student = findStudentById(id)
  
      if (student) {
        // Populate delete modal
        document.getElementById("deleteStudentPhoto").src = student.photo
        document.getElementById("deleteStudentName").textContent = `${student.first_name} ${student.last_name}`
        document.getElementById("deleteStudentEmail").textContent = student.email
  
        // Set delete button data
        confirmDeleteBtn.dataset.id = student.id
  
        // Show delete modal
        deleteModal.style.display = "block"
      }
    }
  
    // Close Delete Modal
    if (closeDeleteModal) {
      closeDeleteModal.addEventListener("click", () => {
        deleteModal.style.display = "none"
      })
    }
  
    if (cancelDeleteBtn) {
      cancelDeleteBtn.addEventListener("click", () => {
        deleteModal.style.display = "none"
      })
    }
  
    // Confirm Delete
    if (confirmDeleteBtn) {
      confirmDeleteBtn.addEventListener("click", () => {
        const studentId = confirmDeleteBtn.dataset.id
  
        // In a real application, you would send a delete request to the server
        // For demo purposes, we'll just show a success message
        console.log("Student deleted successfully!") // Changed from alert to console.log
  
        // Close modal
        deleteModal.style.display = "none"
  
        // In a real application, you would refresh the page or update the UI
        // For demo purposes, we'll just reload the page
        window.location.reload()
      })
    }
  
    // Close modals when clicking outside
    window.addEventListener("click", (e) => {
      if (e.target === studentModal) {
        studentModal.style.display = "none"
      }
      if (e.target === viewStudentModal) {
        viewStudentModal.style.display = "none"
      }
      if (e.target === deleteModal) {
        deleteModal.style.display = "none"
      }
    })
  
    // Helper function to find student by ID
    function findStudentById(id) {
      // In a real application, you would fetch this from the server
      // For demo purposes, we'll use the sample data
      return students.find((student) => student.id == id)
    }
  
    // Helper function to format date
    function formatDate(dateString) {
      const options = { year: "numeric", month: "long", day: "numeric" }
      return new Date(dateString).toLocaleDateString(undefined, options)
    }
  
    // Mobile sidebar toggle - MODIFIED to not interfere with navigation
    const menuToggle = document.querySelector(".menu-toggle")
    if (menuToggle) {
      menuToggle.addEventListener("click", () => {
        const sidebar = document.querySelector(".sidebar")
        sidebar.classList.toggle("active")
      })
    }
  })
  