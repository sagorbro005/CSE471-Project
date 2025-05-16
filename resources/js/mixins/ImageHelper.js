/**
 * Image Helper Mixin
 * Provides consistent image URL handling across the application
 */

export const imageHelper = {
  methods: {
    /**
     * Format image URL to ensure it works in all environments
     * @param {string} path - The image path from the database
     * @param {string} defaultImage - Default image to show if path is empty
     * @returns {string} - Properly formatted image URL
     */
    getImageUrl(path, defaultImage = '/images/default.png') {
      if (!path) {
        console.log('Empty image path, using default:', defaultImage);
        return defaultImage;
      }

      console.log('Processing image path:', path);

      // Check for Cloudinary URLs first (critical for deployed version)
      if (path.includes('cloudinary.com')) {
        console.log('Detected Cloudinary URL, using as-is');
        return path; // Return Cloudinary URL as is
      }

      // If it's already an absolute URL, return as is
      if (path.startsWith('http://') || path.startsWith('https://')) {
        console.log('Detected absolute URL, using as-is');
        return path;
      }

      // Handle public image paths (like /images/slider/slide1.jpg)
      if (path.startsWith('/images/') || path.match(/^\/images\//i)) {
        console.log('Detected public images path');
        return path; // These are directly in the public directory
      }

      // If it starts with /storage, just use as is
      if (path.startsWith('/storage/')) {
        console.log('Detected path with /storage/ prefix');
        return path;
      }

      // Otherwise, ensure it's properly formatted with /storage/ prefix
      console.log('Adding /storage/ prefix to path');
      return `/storage/${path.replace(/^\/+/, '')}`;
    }
  }
};
