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
        return defaultImage;
      }

      // If it's already an absolute URL, return as is
      if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
      }

      // Handle public image paths (like /images/slider/slide1.jpg)
      if (path.startsWith('/images/') || path.match(/^\/images\//i)) {
        return path; // These are directly in the public directory
      }

      // If it starts with /storage, just use as is
      if (path.startsWith('/storage/')) {
        return path;
      }

      // Otherwise, ensure it's properly formatted with /storage/ prefix
      return `/storage/${path.replace(/^\/+/, '')}`;
    }
  }
};
