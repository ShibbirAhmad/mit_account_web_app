// const base_url = 'http://localhost:8000/v1/api/client';

// export default base_url;


// Get the meta tag elements
const localBaseUrl = document.head.querySelector('meta[name="local_base_url"]').content;
const productionBaseUrl = document.head.querySelector('meta[name="production_base_url"]').content;

// Check if we're in production or local based on the hostname
const isLocal = window.location.hostname === 'localhost';

// Conditionally set the base URL
const base_url = isLocal ? localBaseUrl : productionBaseUrl;

export default base_url;