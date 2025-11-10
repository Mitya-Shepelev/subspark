-- ===================================================================
-- SubSpark Database Indexes Optimization
-- Date: 10 November 2025
-- Purpose: Add composite indexes to speed up JOIN queries
-- Expected improvement: +30-50% query speed
-- ===================================================================

-- Before running: BACKUP YOUR DATABASE!
-- Run this in MySQL:
-- mysql -u username -p database_name < database_indexes_optimization.sql

USE subspark;

-- ===================================================================
-- 1. i_posts - Post queries optimization
-- ===================================================================

-- For reels filtering (used in iN_AllUserProfilePostsByChooseReels)
ALTER TABLE i_posts
ADD INDEX idx_post_owner_type_status (post_owner_id, post_type, post_status);

-- For post status filtering
ALTER TABLE i_posts
ADD INDEX idx_post_status (post_status);

-- ===================================================================
-- 2. i_post_likes - Like queries optimization (CRITICAL - no indexes!)
-- ===================================================================

-- For checking if user liked a post (used in htmlPosts.php)
ALTER TABLE i_post_likes
ADD INDEX idx_post_user_like (post_id_fk, iuid_fk);

-- For counting likes per post (used in LEFT JOIN)
ALTER TABLE i_post_likes
ADD INDEX idx_post_likes_count (post_id_fk);

-- ===================================================================
-- 3. i_post_comments - Comment queries optimization
-- ===================================================================

-- For counting comments per post (used in LEFT JOIN)
-- Note: ixComment exists (comment_post_id_fk, comment_uid_fk)
-- Adding single column index for COUNT queries
ALTER TABLE i_post_comments
ADD INDEX idx_post_comments_count (comment_post_id_fk);

-- ===================================================================
-- 4. i_user_uploads - Upload filtering optimization
-- ===================================================================

-- For filtering by user, status, type and extension
-- Used in: iN_TotalImagePosts, iN_TotalVideoPosts, iN_TotalAudioPosts
ALTER TABLE i_user_uploads
ADD INDEX idx_user_uploads_filter (iuid_fk, upload_status, upload_type, uploaded_file_ext);

-- ===================================================================
-- 5. i_user_avatars - Avatar lookup optimization (CRITICAL - no indexes!)
-- ===================================================================

-- For finding user avatars (used in iN_UserAvatar)
ALTER TABLE i_user_avatars
ADD INDEX idx_avatar_user (iuid_fk);

-- ===================================================================
-- 6. i_user_covers - Cover lookup optimization (CRITICAL - no indexes!)
-- ===================================================================

-- For finding user covers (used in iN_UserCover)
ALTER TABLE i_user_covers
ADD INDEX idx_cover_user (iuid_fk);

-- ===================================================================
-- 7. i_friends - Friendship queries optimization
-- ===================================================================

-- For finding followers (reverse lookup)
-- Note: ixFriend exists (fr_one, fr_two, fr_status)
-- Adding index for followers counting
ALTER TABLE i_friends
ADD INDEX idx_friends_followers (fr_two, fr_status);

-- ===================================================================
-- 8. i_user_subscriptions - Subscription queries optimization
-- ===================================================================

-- For counting subscribers by profile
-- Note: ix_Subscribe exists (iuid_fk, subscribed_iuid_fk, status)
-- Adding index for subscriber counting
ALTER TABLE i_user_subscriptions
ADD INDEX idx_subscriptions_count (subscribed_iuid_fk, status);

-- ===================================================================
-- 9. i_user_product_posts - Product queries optimization
-- ===================================================================

-- For counting products by user (used in iN_GetProfileStats)
ALTER TABLE i_user_product_posts
ADD INDEX idx_product_user (iuid_fk);

-- ===================================================================
-- 10. Additional optimizations
-- ===================================================================

-- For user status checks (frequently used in WHERE clauses)
ALTER TABLE i_users
ADD INDEX idx_user_status (uStatus);

-- For user verification status
ALTER TABLE i_users
ADD INDEX idx_user_verified (user_verified_status);

-- ===================================================================
-- Verification: Show all new indexes
-- ===================================================================

-- Run these after applying changes to verify:
-- SHOW INDEX FROM i_posts;
-- SHOW INDEX FROM i_post_likes;
-- SHOW INDEX FROM i_post_comments;
-- SHOW INDEX FROM i_user_uploads;
-- SHOW INDEX FROM i_user_avatars;
-- SHOW INDEX FROM i_user_covers;
-- SHOW INDEX FROM i_friends;
-- SHOW INDEX FROM i_user_subscriptions;
-- SHOW INDEX FROM i_user_product_posts;
-- SHOW INDEX FROM i_users;

-- ===================================================================
-- Performance Testing
-- ===================================================================

-- After applying indexes, test with EXPLAIN:
-- EXPLAIN SELECT COUNT(*) FROM i_post_likes WHERE post_id_fk = 1;
-- EXPLAIN SELECT * FROM i_user_avatars WHERE iuid_fk = 1;
-- EXPLAIN SELECT * FROM i_user_covers WHERE iuid_fk = 1;

-- ===================================================================
-- Rollback (if needed)
-- ===================================================================

/*
-- Run these if you need to remove the indexes:

ALTER TABLE i_posts DROP INDEX idx_post_owner_type_status;
ALTER TABLE i_posts DROP INDEX idx_post_status;
ALTER TABLE i_post_likes DROP INDEX idx_post_user_like;
ALTER TABLE i_post_likes DROP INDEX idx_post_likes_count;
ALTER TABLE i_post_comments DROP INDEX idx_post_comments_count;
ALTER TABLE i_user_uploads DROP INDEX idx_user_uploads_filter;
ALTER TABLE i_user_avatars DROP INDEX idx_avatar_user;
ALTER TABLE i_user_covers DROP INDEX idx_cover_user;
ALTER TABLE i_friends DROP INDEX idx_friends_followers;
ALTER TABLE i_user_subscriptions DROP INDEX idx_subscriptions_count;
ALTER TABLE i_user_product_posts DROP INDEX idx_product_user;
ALTER TABLE i_users DROP INDEX idx_user_status;
ALTER TABLE i_users DROP INDEX idx_user_verified;
*/

-- ===================================================================
-- END OF MIGRATION
-- ===================================================================
