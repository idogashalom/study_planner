document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.querySelector('.sidebar');
  const mobileMenuBtn = document.createElement('button');
  
  // Only add mobile toggle button on mobile screens
  if (window.innerWidth <= 768) {
    mobileMenuBtn.innerHTML = '<i class="bx bx-menu"></i>';
    mobileMenuBtn.classList.add('mobile-menu-btn');
    document.body.appendChild(mobileMenuBtn);
    
    mobileMenuBtn.addEventListener('click', function() {
      sidebar.classList.toggle('mobile-open');
    });
    
    // Close sidebar when clicking outside
    document.addEventListener('click', function(e) {
      if (!sidebar.contains(e.target) && e.target !== mobileMenuBtn) {
        sidebar.classList.remove('mobile-open');
      }
    });
  }
  
  // Responsive adjustment
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
      sidebar.classList.remove('mobile-open');
      if (document.querySelector('.mobile-menu-btn')) {
        document.querySelector('.mobile-menu-btn').remove();
      }
    } else {
      if (!document.querySelector('.mobile-menu-btn')) {
        document.body.appendChild(mobileMenuBtn);
      }
    }
  });
});