# 🎧 Podcast System Advanced

A Laravel-based podcast management system with the following features:

## 🚀 Features

1. Podcasts & Comments
   - Create podcasts with audio files and optional cover images.
   - Each podcast can have top-level and nested (threaded) comments.

2. Factories & Seeders
   - Factories for podcasts and comments.
   - Seeders assign a random number of comments to each podcast.

3. Podcast Likes
   - Users can like/unlike any podcast.
   - Likes are toggled efficiently per user.

4. Categories
   - Podcasts can be associated with multiple categories (many-to-many).
   - Categories are created via seeders.

5. Random Podcast Listing
   - Endpoint to retrieve a random list of podcasts with pagination.
