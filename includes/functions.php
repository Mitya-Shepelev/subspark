<?php
class iN_UPDATES {
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}
	/*Get Site Configurations included in inc.php file
		* If you add some new row you can include your row in inc.php file
		* then call your new row from anywhere
		* OPTIMIZED: Uses Redis cache to avoid DB query on every request
	*/
	public function iN_Configurations() {
		// Try to get from cache first
		$cacheKey = 'config:main';
		$cached = Cache::get($cacheKey);

		if ($cached !== false) {
			return $cached;
		}

		// Cache miss - fetch from DB
		$config = DB::one("SELECT * FROM i_configurations WHERE configuration_id = 1");

		// Store in cache for 1 hour
		if ($config) {
			Cache::set($cacheKey, $config, 3600);
		}

		return $config;
	}
	/* Get Payment Methods List
		*  When user buy point the methods will be appear user screen
		*  You can see as follow query showing just payment_method_id's 1
		*  Admin can set it 1 to 0
		*  0 means inactive
	*/
	public function iN_PaymentMethods() {
		return DB::one("SELECT * FROM i_payment_methods WHERE payment_method_id = 1");
	}
	/* Check username
		*  If username is exist return true
		*  If username is not exist return false
	*/
	public function iN_CheckUserName($username) {
		return (bool) DB::col("SELECT 1 FROM i_users WHERE i_username = ? LIMIT 1", [$username]);
	}
	/* Check Username Exist from register page
		* It is different then above function iN_CheckUserName becaose of
		* iN_CheckUserName also checking userStatus
		* But following code is checking just username
	*/
	public function iN_CheckUsernameExistForRegister($username) {
		return (bool) DB::col("SELECT 1 FROM i_users WHERE i_username = ? LIMIT 1", [$username]);
	}
	/* Check Email exist when user register
		* If Email exist then return true
		* If email not exist then return false
		* If return true that means user can not register
		* If return false that means user can register
	*/
	public function iN_CheckEmailExistForRegister($email) {
		return (bool) DB::col("SELECT 1 FROM i_users WHERE i_user_email = ? LIMIT 1", [$email]);
	}
	/*
		* Users can view and use existing languages with the following function.
		* The important thing here is the state of the language. If the language status value is 1, it can be used, if it is 0 it cannot be used.
		* The administrator can change the usage of languages.
	*/
	public function iN_Languages() {
        $rows = DB::all("SELECT * FROM i_langs WHERE lang_status = '1'");
        return !empty($rows) ? $rows : false;
    }

	public function iN_CheckLangIDExist($langID) {
		$row = DB::one("SELECT lang_name FROM i_langs WHERE lang_id = ? AND lang_status = '1' LIMIT 1", [(int)$langID]);
		return $row ? $row['lang_name'] : false;
	}

	/*If you add a new icon from database using admin dashboard
	You should call the icon id using the following Function*/
	public function iN_SelectedMenuIcon($icon_id) {
        $row = DB::one("SELECT icon_code FROM i_svg_icons WHERE icon_status = '1' AND icon_id = ? LIMIT 1", [(int)$icon_id]);
        return isset($row['icon_code']) ? $row['icon_code'] : false;
    }
	public function iN_GetUserIDFromSessionKey($sessionKey) {
		$row = DB::one("SELECT session_uid FROM i_sessions WHERE session_key = ? LIMIT 1", [$sessionKey]);
		return $row ? $row['session_uid'] : false;
	}
	public function iN_GetUserDetails($userID) {
		// Try to get from cache first
		$cacheKey = 'user:id:' . (int)$userID;
		$cached = Cache::get($cacheKey);
		if ($cached !== false) {
			return $cached;
		}

		// If not in cache, fetch from DB
		$user = DB::one("SELECT * FROM i_users WHERE iuid = ? LIMIT 1", [(int)$userID]);

		// Cache for 5 minutes (300 seconds)
		if ($user) {
			Cache::set($cacheKey, $user, 300);
		}

		return $user;
	}
	public function iN_Sen($mycd, $mycdStatus,$base_url){
		$check = preg_match('/(.*)-(.*)-(.*)-(.*)-(.*)/', $mycd);
		if($check && $mycdStatus == '1'){
           return true;
        }else{
           return header('Location: ' . route_url(base64_decode('YmVsZWdhbD9zdGVwPXJlcXVpcmVtZW50cw==')));
        }
	}
	/**
     * Checks whether a given user ID exists in the system.
     * Returns true if found, otherwise false.
     */
	public function iN_CheckUsernameExist($username) {
		return DB::col("SELECT 1 FROM i_users WHERE i_username = ? LIMIT 1", [$username]) ? 'yes' : 'no';
	}

	public function iN_GetUserDetailsFromUsername($username) {
		// Try to get from cache first
		$cacheKey = 'user:username:' . $username;
		$cached = Cache::get($cacheKey);
		if ($cached !== false) {
			return $cached;
		}

		// If not in cache, fetch from DB
		$user = DB::one("SELECT * FROM i_users WHERE i_username = ? LIMIT 1", [$username]);

		// Cache for 5 minutes (300 seconds)
		if ($user) {
			Cache::set($cacheKey, $user, 300);
			// Also cache by user ID for consistency
			Cache::set('user:id:' . $user['iuid'], $user, 300);
		}

		return $user;
	}

	public function iN_SenSec($mycd, $mycdStatus){
		$check = preg_match('/(.*)-(.*)-(.*)-(.*)-(.*)/', $mycd);
		if($check == 0 && ($mycdStatus == 1 || $mycdStatus == '' || empty($mycdStatus))){
           return 'go';
		}else{
		   return 'stop';
		}
	}

	public function iN_UserFullName($userID) {
		$row = DB::one("SELECT i_user_fullname, i_username FROM i_users WHERE iuid = ? AND uStatus IN('1','3') LIMIT 1", [(int)$userID]);
		if ($row) {
			$myUsername = $row['i_username'];
			$fullName = $row['i_user_fullname'] ?: $myUsername;
			return ucfirst($fullName);
		}
		return ucfirst($userID);
	}
	public function iN_GetUserName($userID) {
		$name = DB::col("SELECT i_username FROM i_users WHERE iuid = ? AND uStatus IN('1','3') LIMIT 1", [(int)$userID]);
		return $name ?: false;
	}
	public function iN_UserAvatar($uid, $base_url, $storageConfig = null) {
		$row = DB::one("SELECT user_avatar, login_with, user_gender FROM i_users WHERE iuid = ? LIMIT 1", [(int)$uid]);

		// Use provided storage config or load from global $inc to avoid extra DB query
		if ($storageConfig === null) {
			global $inc;
			$storageConfig = $inc;
		}

		$rowAvatar = isset($row['user_avatar']) ? $row['user_avatar'] : NULL;
		$loginWith = isset($row['login_with']) ? $row['login_with'] : NULL;
		$avatarPath = $this->iN_GetUploadedAvatarURL($uid, $rowAvatar);
		$s3Status = isset($storageConfig['s3_status']) ? $storageConfig['s3_status'] : '0';
		$wasStatus = isset($storageConfig['was_status']) ? $storageConfig['was_status'] : '0';
		$oceanStatus = isset($storageConfig['ocean_status']) ? $storageConfig['ocean_status'] : '0';
		$userGender = isset($row['user_gender']) ? $row['user_gender'] : NULL;
		if (!empty($rowAvatar)) {
			if (!empty($loginWith) && !is_numeric($rowAvatar)) {
				$data = $rowAvatar;
			} else {
				// Use unified storage_public_url if available
				if (function_exists('storage_public_url') && $avatarPath) {
					$data = storage_public_url($avatarPath);
				} else if ($s3Status == 1) {
					$data = 'https://' . $storageConfig['s3_bucket'] . '.s3.' . $storageConfig['s3_region'] . '.amazonaws.com/' . $avatarPath;
				}else if($wasStatus == 1){
					$data = 'https://' . $storageConfig['was_bucket'] . '.s3.' . $storageConfig['was_region'] . '.wasabisys.com/' . $avatarPath;
				}else if($oceanStatus == 1){
					$data = 'https://'.$storageConfig['ocean_space_name'].'.'.$storageConfig['ocean_region'].'.digitaloceanspaces.com/'. $avatarPath;
				} else {
					if ($avatarPath) {
						$data = $base_url . $avatarPath;
					} else {
						$data = $base_url . $rowAvatar;
					}
				}
			}
			return $data;
		} else {
			if ($userGender == 'male') {
				$data = $base_url . "uploads/avatars/d-avatar.jpg";
				return $data;
			} else if ($userGender == 'female') {
				$data = $base_url . "uploads/avatars/f-avatar.jpg";
				return $data;
			} else if ($userGender == 'couple') {
				$data = $base_url . "uploads/avatars/g-couple.png";
				return $data;
			} else {
				$data = $base_url . "uploads/avatars/no_gender.png";
				return $data;
			}
		}
	}
	public function iN_UserCover($uid, $base_url, $storageConfig = null) {
		$row = DB::one("SELECT user_cover, user_gender FROM i_users WHERE iuid = ? LIMIT 1", [(int)$uid]);

		// Use provided storage config or load from global $inc to avoid extra DB query
		if ($storageConfig === null) {
			global $inc;
			$storageConfig = $inc;
		}

		$coverPath = $this->iN_GetUploadedCoverURL($uid, $row['user_cover']);
		$s3Status = isset($storageConfig['s3_status']) ? $storageConfig['s3_status'] : '0';
		$wasStatus = isset($storageConfig['was_status']) ? $storageConfig['was_status'] : '0';
		$oceanStatus = isset($storageConfig['ocean_status']) ? $storageConfig['ocean_status'] : '0';
		$userGender = isset($row['user_gender']) ? $row['user_gender'] : NULL;
		$rowCover = isset($row['user_cover']) ? $row['user_cover'] : NULL;
		if (!empty($rowCover)) {
			// Use unified storage_public_url if available
			if (function_exists('storage_public_url') && $coverPath) {
				$data = storage_public_url($coverPath);
			} else if ($s3Status == 1) {
				$data = 'https://' . $storageConfig['s3_bucket'] . '.s3.' . $storageConfig['s3_region'] . '.amazonaws.com/' . $coverPath;
			}else if($wasStatus == 1){
				$data = 'https://' . $storageConfig['was_bucket'] . '.s3.' . $storageConfig['was_region'] . '.wasabisys.com/' . $coverPath;
			}else if($oceanStatus == 1){
				$data = 'https://'.$storageConfig['ocean_space_name'].'.'.$storageConfig['ocean_region'].'.digitaloceanspaces.com/'. $coverPath;
			} else {
				$data = $base_url . $coverPath;
			}
			return $data;
		} else {
			if ($userGender == 'male') {
				$data = $base_url . "uploads/covers/male.png";
				return $data;
			} else if ($userGender == 'female') {
				$data = $base_url . "uploads/covers/female.png";
				return $data;
			} else if ($userGender == 'couple') {
				$data = $base_url . "uploads/covers/couple.png";
				return $data;
			} else {
				$data = $base_url . "uploads/covers/no_gender.png";
				return $data;
			}
		}
	}
	public function getBaseUrl() {
		$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        return rtrim($baseUrl, '/') . dirname($currentPath);
	}
	public function iN_PurUCheck($userID, $code, $sUrl){
        if($this->iN_CheckIsAdmin($userID) == 1){
            $server = $_SERVER['SERVER_NAME'] ?? '';
            $sUrl = $this->getBaseUrl() ?? '';

            if(empty($server) || empty($sUrl)){
                return false;
            }

            $url = base64_decode('aHR0cHM6Ly9jaGVjay5kaXp6eXNjcmlwdHMuY29tL2NoZWNrZXIucGhwP2NvZGU9') . $code . '&url=' . urlencode($sUrl) . '&server=' . urlencode($server);

            $arrContextOptions = [
                "ssl" => [
                    "verify_peer" => true,
                    "verify_peer_name" => true,
                    "allow_self_signed" => false
                ]
            ];

            $file = @file_get_contents($url, false, stream_context_create($arrContextOptions));

            if($file === false){
                return false;
            }

            $checks = json_decode($file, true);
            $data = $checks['data'] ?? false;

            return $data;
        } else {
            return false;
        }
    }

	public function iN_GetPages() {
        $rows = DB::all("SELECT * FROM i_pages");
        return !empty($rows) ? $rows : null;
	}
	/*Social Logins for Users*/
	public function iN_SocialLogins() {
        $rows = DB::all("SELECT * FROM i_social_logins WHERE s_status = '1'");
        return !empty($rows) ? $rows : null;
	}

	public function iN_LegDone($vc){
  DB::exec("UPDATE i_configurations SET mycd = ?, mycd_status = '1' WHERE configuration_id = 1", [ (string)$vc ]);
  return true;
	}
	/*Social Logins for Admin Page*/
	public function iN_SocialLoginsList() {
        $rows = DB::all("SELECT * FROM i_social_logins");
        return !empty($rows) ? $rows : null;
	}
	public function iN_SocialLoginDetails($scial) {
        return DB::one("SELECT * FROM i_social_logins WHERE s_key = ? LIMIT 1", [$scial]);
	}
	public function iN_GetPCo() {
        $val = DB::col("SELECT mycd FROM i_configurations WHERE configuration_id = 1");
        return $val !== false ? $val : false;
	}
	public function iN_CheckpageExist($pageName) {
        return (bool) DB::col("SELECT 1 FROM i_pages WHERE page_name = ? LIMIT 1", [$pageName]);
	}
	public function iN_CheckpageExistByID($pageID) {
        return (bool) DB::col("SELECT 1 FROM i_pages WHERE page_id = ? LIMIT 1", [(int)$pageID]);
	}
	public function iN_CheckQAExistByID($pageID) {
        return (bool) DB::col("SELECT 1 FROM i_landing_qa WHERE qa_id = ? LIMIT 1", [(int)$pageID]);
	}
	public function iN_GetPageWords($pageName) {
        $row = DB::one("SELECT page_inside FROM i_pages WHERE page_name = ? LIMIT 1", [$pageName]);
        return $row ? $row['page_inside'] : false;
	}
	/*Check User Exist*/
	public function iN_CheckUserExist($uid) {
        return (bool) DB::col("SELECT 1 FROM i_users WHERE iuid = ? LIMIT 1", [(int)$uid]);
	}
	/*Check User Exist*/
	public function iN_CheckIsAdmin($uid) {
        return (bool) DB::col("SELECT 1 FROM i_users WHERE iuid = ? AND userType IN('2','3') LIMIT 1", [(int)$uid]);
	}
	/*Get Total Unreaded notifications */
	public function iN_GetNewNotificationSum($uid) {
		$uid = (int)$uid;
		if ($this->iN_CheckUserExist($uid) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_notifications WHERE not_own_iuid = ? AND not_status = '0'", [$uid]);
            return (int)$val;
		}
		return false;
	}
	/*Get All Notifications*/
	public function iN_GetAllNotificationList($uid, $limit) {
		$uid = (int)$uid; $limit = (int)$limit;
		if ($this->iN_CheckUserExist($uid) == 1) {
            $sql = "SELECT * FROM i_user_notifications N FORCE INDEX(ixForceID)
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON N.not_own_iuid = U.iuid
                WHERE not_own_iuid = ? AND not_status IN('0','1') AND not_show_hide <> '1' ORDER BY N.not_id DESC LIMIT $limit";
            $rows = DB::all($sql, [$uid]);
            return !empty($rows) ? $rows : false;
		}
	}

	/*Update User Notification Status*/
	public function iN_UpdateNotificationStatus($uid) {
		$uid = (int)$uid;
		if ($this->iN_CheckUserExist($uid) == 1) {
            DB::exec("UPDATE i_user_notifications SET not_status = '1' WHERE not_own_iuid = ?", [$uid]);
            DB::exec("UPDATE i_users SET notification_read_status = '0' WHERE iuid = ?", [$uid]);
            return true;
		}
		return false;
	}
	/*Check Notification ID Exist*/
	public function iN_CheckNotificationIDExist($notID) {
        return (bool) DB::col("SELECT 1 FROM i_user_notifications WHERE not_id = ? LIMIT 1", [(int)$notID]);
	}
	/*Get More Notifications*/
	public function iN_GetMoreNotificationList($uid, $limit, $lastID) {
		$uid = (int)$uid; $limit = (int)$limit; $lastID = (int)$lastID;
		$moreData = '';
		if ($lastID) { $moreData = ' AND N.not_id < ' . $lastID . ' '; }
		if ($this->iN_CheckUserExist($uid) == 1 && $this->iN_CheckNotificationIDExist($lastID) == 1) {
            $sql = "SELECT * FROM i_user_notifications N FORCE INDEX(ixForceID)
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON N.not_own_iuid = U.iuid
                WHERE not_own_iuid = ? AND not_status IN('0','1') $moreData ORDER BY N.not_id DESC LIMIT $limit";
            $rows = DB::all($sql, [$uid]);
            return !empty($rows) ? $rows : null;
		}
	}

public function iN_CheckPostIDExist($postID) {
        return (bool) DB::col("SELECT 1 FROM i_posts WHERE post_id = ? LIMIT 1", [(int)$postID]);
}

public function iN_CheckImageIDExist($ImageID, $userID) {
        return (bool) DB::col("SELECT 1 FROM i_user_uploads WHERE iuid_fk = ? AND upload_id = ? LIMIT 1", [(int)$userID, (int)$ImageID]);
}

public function iN_UpdateWhoCanSeePost($uid, $whoID) {
        if ($this->iN_CheckUserExist($uid) == 1) {
            DB::exec("UPDATE i_users SET post_who_can_see = ? WHERE iuid = ?", [(string)$whoID, (int)$uid]);
            return true;
        }
        return false;
}
	/*INSERT UPLOADED FILES FROM UPLOADS TABLE*/
public function iN_INSERTUploadedFiles($uid, $filePath, $tumbnailPath, $fileXPath, $ext) {
        $uploadTime = time();
        $userIP = $_SERVER['REMOTE_ADDR'] ?? '';
        DB::exec(
            "INSERT INTO i_user_uploads (iuid_fk, uploaded_file_path, upload_tumbnail_file_path, uploaded_x_file_path, uploaded_file_ext, upload_time, ip)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [(int)$uid, (string)$filePath, (string)$tumbnailPath, (string)$fileXPath, (string)$ext, $uploadTime, (string)$userIP]
        );
        return (int) DB::lastId();
}

public function iN_GetUploadedFilesIDs($uid, $imageName) {
        if (!$imageName) { return false; }
        $row = DB::one("SELECT upload_id, uploaded_file_path, upload_tumbnail_file_path FROM i_user_uploads WHERE iuid_fk = ? ORDER BY upload_id DESC LIMIT 1", [(int)$uid]);
        return $row ?: false;
}
	/*INSERT NEW POST AND GET REAL TIME*/
    public function iN_InsertNewPost($uid, $postText, $urlSlug, $postFiles, $postWhoCanSee, $hashTags, $pointAmount,$autoApprovePostStatus) {
		$time = time();
		$userIP = $_SERVER['REMOTE_ADDR'] ?? '';
		$postStatus = '1';
		if (!empty($postFiles) && trim($postFiles) === '') { $postStatus = '1'; }
		if ($postWhoCanSee == '4' && $autoApprovePostStatus == 'no') { $postStatus = '2'; }
		DB::exec(
			"INSERT INTO i_posts (post_owner_id,post_text,post_file,post_created_time,post_creator_ip,who_can_see,post_status,url_slug,hashtags,post_wanted_credit)
			 VALUES (?,?,?,?,?,?,?,?,?,?)",
			[(int)$uid,(string)$postText,(string)$postFiles,$time,(string)$userIP,(string)$postWhoCanSee,(string)$postStatus,(string)$urlSlug,(string)$hashTags,(string)$pointAmount]
		);
		$result = DB::one(
			"SELECT P.post_id,P.shared_post_id,P.post_pined,P.post_owner_id,P.post_text,P.hashtags,P.post_file,P.post_created_time,P.who_can_see,P.post_want_status,P.post_wanted_credit,P.url_slug,P.post_status,P.comment_status,U.payout_method,U.iuid,U.i_username,U.i_user_fullname,U.user_avatar,U.user_gender,U.payout_method,U.last_login_time,U.user_verified_status,U.thanks_for_tip
			 FROM i_posts P FORCE INDEX(ixForcePostOwner)
			 INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.post_owner_id = U.iuid
			 WHERE post_status IN('0','1','2') ORDER BY P.post_id DESC LIMIT 1"
		);
		$newPostID = $result['post_id'] ?? null;
		if ($newPostID) { $this->iN_InsertPostActivity($uid, 'newPost', $newPostID, $time); }
		return $result;
    }
	/*Get All Friens and My Posts*/
    public function iN_AllFriendsPosts($uid, $lastPostID, $showingPost) {
        $showingPosts = (int)($showingPost ?? 0);
        $params = [(int)$uid];
        $more = '';
        if (!empty($lastPostID)) { $more = ' AND P.post_id < ? '; $params[] = (int)$lastPostID; }

        // OPTIMIZED: Added LEFT JOINs for likes count, comments count, and user like status
        // This eliminates N+1 queries in the display loop
        $sql = "SELECT DISTINCT P.post_id,P.shared_post_id,P.post_pined,P.comment_status,
                P.post_owner_id,P.post_text,P.post_file,P.post_created_time,
                P.who_can_see,P.post_want_status,P.url_slug,P.post_wanted_credit,
                P.post_status,P.hashtags,
                U.iuid,U.i_username,U.i_user_fullname,U.user_avatar,U.user_gender,
                U.payout_method,U.last_login_time,U.user_verified_status,
                U.thanks_for_tip,U.user_frame,U.profile_category,
                IFNULL(likes.total_likes, 0) AS total_likes,
                IFNULL(comments.total_comments, 0) AS total_comments,
                IF(user_likes.like_id IS NOT NULL, 1, 0) AS liked_by_user
            FROM i_friends F
            INNER JOIN i_posts P ON P.post_owner_id = F.fr_two AND P.post_type NOT IN('reels')
            INNER JOIN i_users U ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3') AND F.fr_status IN('me','flwr','subscriber')
            LEFT JOIN (
                SELECT post_id_fk, COUNT(*) AS total_likes
                FROM i_post_likes
                GROUP BY post_id_fk
            ) likes ON P.post_id = likes.post_id_fk
            LEFT JOIN (
                SELECT comment_post_id_fk, COUNT(*) AS total_comments
                FROM i_post_comments
                GROUP BY comment_post_id_fk
            ) comments ON P.post_id = comments.comment_post_id_fk
            LEFT JOIN i_post_likes user_likes ON P.post_id = user_likes.post_id_fk AND user_likes.iuid_fk = ?
            WHERE F.fr_one = ? $more
            ORDER BY P.post_id DESC
            LIMIT $showingPosts";

        // Add uid twice: once for user_likes JOIN, once for WHERE clause
        $params = [(int)$uid, (int)$uid];
        if (!empty($lastPostID)) { $params[] = (int)$lastPostID; }

        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
    public function iN_AllFriendsPostsOut($lastPostID, $showingPost) {
        $showingPosts = (int)$showingPost;
        $where = '';
        $params = [];
        if (!empty($lastPostID)) { $where = 'WHERE P.post_id < ?'; $params[] = (int)$lastPostID; }
        $sql = "SELECT DISTINCT
                P.post_id, P.post_type, P.shared_post_id, P.post_pined, P.comment_status,
                P.post_owner_id, P.post_text, P.hashtags, P.post_file, P.post_created_time,
                P.who_can_see, P.post_want_status, P.url_slug, P.post_wanted_credit, P.post_status,
                U.iuid, U.i_username, U.i_user_fullname, U.user_avatar, U.user_gender,
                U.payout_method, U.last_login_time, U.user_verified_status, U.thanks_for_tip
            FROM i_friends F FORCE INDEX(ixFriend)
            INNER JOIN i_posts P FORCE INDEX (ixForcePostOwner)
                ON P.post_owner_id = F.fr_two AND P.post_type NOT IN('reels')
            INNER JOIN i_users U FORCE INDEX (ixForceUser)
                ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3') AND F.fr_status IN('me', 'flwr', 'subscriber')
            $where
            ORDER BY P.post_id DESC
            LIMIT $showingPosts";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }

    public function iN_GetPostComments($postID, $second) {
        $limit = '';
        if (!empty($second) && is_numeric($second)) {
            $offset = (int)$second;
            $limit = " LIMIT $offset,2";
        }
        $sql = "SELECT C.com_id,C.comment_uid_fk,C.comment_post_id_fk,C.comment,C.comment_time,C.comment_file,C.sticker_url,C.gif_url,
                       U.iuid,U.i_username,U.i_user_fullname,U.user_avatar,U.user_gender,U.last_login_time,U.user_verified_status,U.user_frame
                FROM i_post_comments C FORCE INDEX(ixComment)
                INNER JOIN i_users U FORCE INDEX(ixForceUser)
                  ON C.comment_uid_fk = U.iuid AND U.uStatus IN('1','3')
                WHERE C.comment_post_id_fk = ?
                ORDER BY C.com_id ASC" . $limit;
        $rows = DB::all($sql, [(int)$postID]);
        return !empty($rows) ? $rows : null;
    }
	/*GET UPLOADED FILE DATA*/
	public function iN_GetUploadedFileDetails($imageID) {
		if (!$imageID) { return false; }
		return DB::one("SELECT * FROM i_user_uploads WHERE upload_id = ? LIMIT 1", [(int)$imageID]);
	}

	public function iN_GetRelationsipBetweenTwoUsers($userOne, $userTwo) {
		$val = DB::col("SELECT fr_status FROM i_friends WHERE fr_one = ? AND fr_two = ? LIMIT 1", [(int)$userOne, (int)$userTwo]);
		return $val !== false ? $val : null;
	}

	public function iN_CheckProfileSubscriptionType($userOne, $userTwo){
		$row = DB::one("SELECT payment_method FROM i_user_subscriptions WHERE iuid_fk = ? AND subscribed_iuid_fk = ? LIMIT 1", [(int)$userOne, (int)$userTwo]);
		return $row ? $row['payment_method'] : null;
	}

	public function iN_CheckPostLikedBefore($userID, $postID) {
		return (bool) DB::col("SELECT 1 FROM i_post_likes WHERE post_id_fk = ? AND iuid_fk = ? LIMIT 1", [(int)$postID, (int)$userID]);
	}

	public function iN_CheckCommentLikedBefore($userID, $postID, $commentID) {
		return (bool) DB::col("SELECT 1 FROM i_post_comment_likes WHERE c_like_post_id = ? AND c_like_iuid_fk = ? AND c_like_comment_id = ? LIMIT 1", [(int)$postID, (int)$userID, (int)$commentID]);
	}

	public function iN_LikePost($userID, $postID) {
		$time = time();
		$userIP = $_SERVER['REMOTE_ADDR'] ?? '';
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
			if ($this->iN_CheckPostLikedBefore($userID, $postID) == 1) {
				DB::exec("DELETE FROM i_post_likes WHERE post_id_fk = ? AND iuid_fk = ?", [(int)$postID, (int)$userID]);
				$this->iN_DeletePostLikeActivity($userID, $postID);
				return false;
			} else {
				$this->iN_InsertPostLikeActivity($userID, 'postLike',$postID, $time);
				DB::exec("INSERT INTO i_post_likes (post_id_fk,iuid_fk,like_time,user_ip) VALUES (?,?,?,?)", [(int)$postID,(int)$userID,$time,(string)$userIP]);
				return true;
			}
		}
	}

	public function iN_GetAllPostDetails($postID) {
        if (!isset($postID) || !is_numeric($postID)) {
            return false;
        }

        if ($this->iN_CheckPostIDExist($postID) == '1') {
            $sql = "SELECT DISTINCT
                    P.post_id, P.shared_post_id, P.post_pined, P.url_slug, P.comment_status, P.hashtags,
                    P.post_owner_id, P.post_text, P.post_file, P.post_created_time, P.who_can_see,
                    P.post_want_status, P.post_wanted_credit, P.post_status, P.post_type,
                    U.iuid, U.i_username, U.i_user_fullname, U.user_avatar, U.user_gender,
                    U.payout_method, U.thanks_for_tip, U.last_login_time, U.user_verified_status,
                    U.profile_status, U.uStatus, U.user_frame
                FROM i_friends F FORCE INDEX(ixFriend)
                INNER JOIN i_posts P FORCE INDEX (ixForcePostOwner) ON P.post_owner_id = F.fr_two
                INNER JOIN i_users U FORCE INDEX (ixForceUser) ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3') AND F.fr_status IN('me','flwr','subscriber')
                WHERE P.post_id = ? ORDER BY P.post_id";
            return DB::one($sql, [(int)$postID]);
        } else {
            return false;
        }
    }

	public function iN_ReShare_Post($userID, $postID, $postDetails) {
		$time = time();
		$userIP = $_SERVER['REMOTE_ADDR'] ?? '';
		if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckPostIDExist($postID) == '1') {
			DB::exec(
				"INSERT INTO i_posts (post_owner_id,post_text,post_created_time,shared_post_id,post_creator_ip)
				 SELECT ?, ?, ?, ?, ? FROM i_posts WHERE post_id = ?",
				[(int)$userID, (string)$postDetails, $time, (int)$postID, (string)$userIP, (int)$postID]
			);
			return true;
		}
		return false;
	}

	public function iN_GetAllIcons() {
		$rows = DB::all("SELECT * FROM i_svg_icons");
		return !empty($rows) ? $rows : null;
	}

	public function iN_CheckFileIDExist($fileID) {
		return (bool) DB::col("SELECT 1 FROM i_user_uploads WHERE upload_id = ? LIMIT 1", [(int)$fileID]);
	}
	/*Delete File Before Publish Post*/
	public function iN_DeleteFile($userID, $fileID) {
		if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckFileIDExist($fileID) == '1') {
			DB::exec("DELETE FROM i_user_uploads WHERE upload_id = ? AND iuid_fk = ?", [(int)$fileID, (int)$userID]);
			return true;
		}
		return false;
	}
	public function iN_fetchDataFromURL($url = '') {
		if (empty($url)) {
			return false;
		}
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		return curl_exec($ch);
	}
	/*URL SLUG*/
	public function url_slugies($str, $options = array()) {
		$str = mb_convert_encoding((string) $str, 'UTF-8', 'auto');
		$defaults = array(
			'delimiter' => '-',
			'limit' => null,
			'lowercase' => true,
			'replacements' => array(),
			'transliterate' => true,
		);
		$options = array_merge($defaults, $options);
		$char_map = array(
			'À' => 'A',
			'Á' => 'A',
			'Â' => 'A',
			'Ã' => 'A',
			'Ä' => 'A',
			'Å' => 'A',
			'Æ' => 'AE',
			'Ç' => 'C',
			'È' => 'E',
			'É' => 'E',
			'Ê' => 'E',
			'Ë' => 'E',
			'Ì' => 'I',
			'Í' => 'I',
			'Î' => 'I',
			'Ï' => 'I',
			'Ð' => 'D',
			'Ñ' => 'N',
			'Ò' => 'O',
			'Ó' => 'O',
			'Ô' => 'O',
			'Õ' => 'O',
			'Ö' => 'O',
			'Ő' => 'O',
			'Ø' => 'O',
			'Ù' => 'U',
			'Ú' => 'U',
			'Û' => 'U',
			'Ü' => 'U',
			'Ű' => 'U',
			'Ý' => 'Y',
			'Þ' => 'TH',
			'ß' => 'ss',
			'à' => 'a',
			'á' => 'a',
			'â' => 'a',
			'ã' => 'a',
			'ä' => 'a',
			'å' => 'a',
			'æ' => 'ae',
			'ç' => 'c',
			'è' => 'e',
			'é' => 'e',
			'ê' => 'e',
			'ë' => 'e',
			'ì' => 'i',
			'í' => 'i',
			'î' => 'i',
			'ï' => 'i',
			'ð' => 'd',
			'ñ' => 'n',
			'ò' => 'o',
			'ó' => 'o',
			'ô' => 'o',
			'õ' => 'o',
			'ö' => 'o',
			'ő' => 'o',
			'ø' => 'o',
			'ù' => 'u',
			'ú' => 'u',
			'û' => 'u',
			'ü' => 'u',
			'ű' => 'u',
			'ý' => 'y',
			'þ' => 'th',
			'ÿ' => 'y',
			'©' => '(c)',
			'Α' => 'A',
			'Β' => 'B',
			'Γ' => 'G',
			'Δ' => 'D',
			'Ε' => 'E',
			'Ζ' => 'Z',
			'Η' => 'H',
			'Θ' => '8',
			'Ι' => 'I',
			'Κ' => 'K',
			'Λ' => 'L',
			'Μ' => 'M',
			'Ν' => 'N',
			'Ξ' => '3',
			'Ο' => 'O',
			'Π' => 'P',
			'Ρ' => 'R',
			'Σ' => 'S',
			'Τ' => 'T',
			'Υ' => 'Y',
			'Φ' => 'F',
			'Χ' => 'X',
			'Ψ' => 'PS',
			'Ω' => 'W',
			'Ά' => 'A',
			'Έ' => 'E',
			'Ί' => 'I',
			'Ό' => 'O',
			'Ύ' => 'Y',
			'Ή' => 'H',
			'Ώ' => 'W',
			'Ϊ' => 'I',
			'Ϋ' => 'Y',
			'α' => 'a',
			'β' => 'b',
			'γ' => 'g',
			'δ' => 'd',
			'ε' => 'e',
			'ζ' => 'z',
			'η' => 'h',
			'θ' => '8',
			'ι' => 'i',
			'κ' => 'k',
			'λ' => 'l',
			'μ' => 'm',
			'ν' => 'n',
			'ξ' => '3',
			'ο' => 'o',
			'π' => 'p',
			'ρ' => 'r',
			'σ' => 's',
			'τ' => 't',
			'υ' => 'y',
			'φ' => 'f',
			'χ' => 'x',
			'ψ' => 'ps',
			'ω' => 'w',
			'ά' => 'a',
			'έ' => 'e',
			'ί' => 'i',
			'ό' => 'o',
			'ύ' => 'y',
			'ή' => 'h',
			'ώ' => 'w',
			'ς' => 's',
			'ϊ' => 'i',
			'ΰ' => 'y',
			'ϋ' => 'y',
			'ΐ' => 'i',
			'Ş' => 'S',
			'İ' => 'I',
			'Ç' => 'C',
			'Ü' => 'U',
			'Ö' => 'O',
			'Ğ' => 'G',
			'ş' => 's',
			'ı' => 'i',
			'ç' => 'c',
			'ü' => 'u',
			'ö' => 'o',
			'ğ' => 'g',
			'А' => 'A',
			'Б' => 'B',
			'В' => 'V',
			'Г' => 'G',
			'Д' => 'D',
			'Е' => 'E',
			'Ё' => 'Yo',
			'Ж' => 'Zh',
			'З' => 'Z',
			'И' => 'I',
			'Й' => 'J',
			'К' => 'K',
			'Л' => 'L',
			'М' => 'M',
			'Н' => 'N',
			'О' => 'O',
			'П' => 'P',
			'Р' => 'R',
			'С' => 'S',
			'Т' => 'T',
			'У' => 'U',
			'Ф' => 'F',
			'Х' => 'H',
			'Ц' => 'C',
			'Ч' => 'Ch',
			'Ш' => 'Sh',
			'Щ' => 'Sh',
			'Ъ' => '',
			'Ы' => 'Y',
			'Ь' => '',
			'Э' => 'E',
			'Ю' => 'Yu',
			'Я' => 'Ya',
			'а' => 'a',
			'б' => 'b',
			'в' => 'v',
			'г' => 'g',
			'д' => 'd',
			'е' => 'e',
			'ё' => 'yo',
			'ж' => 'zh',
			'з' => 'z',
			'и' => 'i',
			'й' => 'j',
			'к' => 'k',
			'л' => 'l',
			'м' => 'm',
			'н' => 'n',
			'о' => 'o',
			'п' => 'p',
			'р' => 'r',
			'с' => 's',
			'т' => 't',
			'у' => 'u',
			'ф' => 'f',
			'х' => 'h',
			'ц' => 'c',
			'ч' => 'ch',
			'ш' => 'sh',
			'щ' => 'sh',
			'ъ' => '',
			'ы' => 'y',
			'ь' => '',
			'э' => 'e',
			'ю' => 'yu',
			'я' => 'ya',
			'Є' => 'Ye',
			'І' => 'I',
			'Ї' => 'Yi',
			'Ґ' => 'G',
			'є' => 'ye',
			'і' => 'i',
			'ї' => 'yi',
			'ґ' => 'g',
			'Č' => 'C',
			'Ď' => 'D',
			'Ě' => 'E',
			'Ň' => 'N',
			'Ř' => 'R',
			'Š' => 'S',
			'Ť' => 'T',
			'Ů' => 'U',
			'Ž' => 'Z',
			'č' => 'c',
			'ď' => 'd',
			'ě' => 'e',
			'ň' => 'n',
			'ř' => 'r',
			'š' => 's',
			'ť' => 't',
			'ů' => 'u',
			'ž' => 'z',
			'Ą' => 'A',
			'Ć' => 'C',
			'Ę' => 'e',
			'Ł' => 'L',
			'Ń' => 'N',
			'Ó' => 'o',
			'Ś' => 'S',
			'Ź' => 'Z',
			'Ż' => 'Z',
			'ą' => 'a',
			'ć' => 'c',
			'ę' => 'e',
			'ł' => 'l',
			'ń' => 'n',
			'ó' => 'o',
			'ś' => 's',
			'ź' => 'z',
			'ż' => 'z',
			'Ā' => 'A',
			'Č' => 'C',
			'Ē' => 'E',
			'Ģ' => 'G',
			'Ī' => 'i',
			'Ķ' => 'k',
			'Ļ' => 'L',
			'Ņ' => 'N',
			'Š' => 'S',
			'Ū' => 'u',
			'Ž' => 'Z',
			'ā' => 'a',
			'č' => 'c',
			'ē' => 'e',
			'ģ' => 'g',
			'ī' => 'i',
			'ķ' => 'k',
			'ļ' => 'l',
			'ņ' => 'n',
			'š' => 's',
			'ū' => 'u',
			'ž' => 'z',
		);
		if (!empty($options['replacements']) && is_array($options['replacements'])) {
            $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
        }

        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }

        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        $str = mb_substr($str, 0, $options['limit'] ?? mb_strlen($str, 'UTF-8'), 'UTF-8');
        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}
	public function url_Hash($str, $options = array()) {
		$str = mb_convert_encoding((string) $str, 'UTF-8', 'auto');
		$defaults = array(
			'delimiter' => ',',
			'limit' => null,
			'lowercase' => true,
			'replacements' => array(),
			'transliterate' => true,
		);
		$options = array_merge($defaults, $options);
		$char_map = array(
			'À' => 'A',
			'Á' => 'A',
			'Â' => 'A',
			'Ã' => 'A',
			'Ä' => 'A',
			'Å' => 'A',
			'Æ' => 'AE',
			'Ç' => 'C',
			'È' => 'E',
			'É' => 'E',
			'Ê' => 'E',
			'Ë' => 'E',
			'Ì' => 'I',
			'Í' => 'I',
			'Î' => 'I',
			'Ï' => 'I',
			'Ð' => 'D',
			'Ñ' => 'N',
			'Ò' => 'O',
			'Ó' => 'O',
			'Ô' => 'O',
			'Õ' => 'O',
			'Ö' => 'O',
			'Ő' => 'O',
			'Ø' => 'O',
			'Ù' => 'U',
			'Ú' => 'U',
			'Û' => 'U',
			'Ü' => 'U',
			'Ű' => 'U',
			'Ý' => 'Y',
			'Þ' => 'TH',
			'ß' => 'ss',
			'à' => 'a',
			'á' => 'a',
			'â' => 'a',
			'ã' => 'a',
			'ä' => 'a',
			'å' => 'a',
			'æ' => 'ae',
			'ç' => 'c',
			'è' => 'e',
			'é' => 'e',
			'ê' => 'e',
			'ë' => 'e',
			'ì' => 'i',
			'í' => 'i',
			'î' => 'i',
			'ï' => 'i',
			'ð' => 'd',
			'ñ' => 'n',
			'ò' => 'o',
			'ó' => 'o',
			'ô' => 'o',
			'õ' => 'o',
			'ö' => 'o',
			'ő' => 'o',
			'ø' => 'o',
			'ù' => 'u',
			'ú' => 'u',
			'û' => 'u',
			'ü' => 'u',
			'ű' => 'u',
			'ý' => 'y',
			'þ' => 'th',
			'ÿ' => 'y',
			'©' => '(c)',
			'Α' => 'A',
			'Β' => 'B',
			'Γ' => 'G',
			'Δ' => 'D',
			'Ε' => 'E',
			'Ζ' => 'Z',
			'Η' => 'H',
			'Θ' => '8',
			'Ι' => 'I',
			'Κ' => 'K',
			'Λ' => 'L',
			'Μ' => 'M',
			'Ν' => 'N',
			'Ξ' => '3',
			'Ο' => 'O',
			'Π' => 'P',
			'Ρ' => 'R',
			'Σ' => 'S',
			'Τ' => 'T',
			'Υ' => 'Y',
			'Φ' => 'F',
			'Χ' => 'X',
			'Ψ' => 'PS',
			'Ω' => 'W',
			'Ά' => 'A',
			'Έ' => 'E',
			'Ί' => 'I',
			'Ό' => 'O',
			'Ύ' => 'Y',
			'Ή' => 'H',
			'Ώ' => 'W',
			'Ϊ' => 'I',
			'Ϋ' => 'Y',
			'α' => 'a',
			'β' => 'b',
			'γ' => 'g',
			'δ' => 'd',
			'ε' => 'e',
			'ζ' => 'z',
			'η' => 'h',
			'θ' => '8',
			'ι' => 'i',
			'κ' => 'k',
			'λ' => 'l',
			'μ' => 'm',
			'ν' => 'n',
			'ξ' => '3',
			'ο' => 'o',
			'π' => 'p',
			'ρ' => 'r',
			'σ' => 's',
			'τ' => 't',
			'υ' => 'y',
			'φ' => 'f',
			'χ' => 'x',
			'ψ' => 'ps',
			'ω' => 'w',
			'ά' => 'a',
			'έ' => 'e',
			'ί' => 'i',
			'ό' => 'o',
			'ύ' => 'y',
			'ή' => 'h',
			'ώ' => 'w',
			'ς' => 's',
			'ϊ' => 'i',
			'ΰ' => 'y',
			'ϋ' => 'y',
			'ΐ' => 'i',
			'Ş' => 'S',
			'İ' => 'I',
			'Ç' => 'C',
			'Ü' => 'U',
			'Ö' => 'O',
			'Ğ' => 'G',
			'ş' => 's',
			'ı' => 'i',
			'ç' => 'c',
			'ü' => 'u',
			'ö' => 'o',
			'ğ' => 'g',
			'А' => 'A',
			'Б' => 'B',
			'В' => 'V',
			'Г' => 'G',
			'Д' => 'D',
			'Е' => 'E',
			'Ё' => 'Yo',
			'Ж' => 'Zh',
			'З' => 'Z',
			'И' => 'I',
			'Й' => 'J',
			'К' => 'K',
			'Л' => 'L',
			'М' => 'M',
			'Н' => 'N',
			'О' => 'O',
			'П' => 'P',
			'Р' => 'R',
			'С' => 'S',
			'Т' => 'T',
			'У' => 'U',
			'Ф' => 'F',
			'Х' => 'H',
			'Ц' => 'C',
			'Ч' => 'Ch',
			'Ш' => 'Sh',
			'Щ' => 'Sh',
			'Ъ' => '',
			'Ы' => 'Y',
			'Ь' => '',
			'Э' => 'E',
			'Ю' => 'Yu',
			'Я' => 'Ya',
			'а' => 'a',
			'б' => 'b',
			'в' => 'v',
			'г' => 'g',
			'д' => 'd',
			'е' => 'e',
			'ё' => 'yo',
			'ж' => 'zh',
			'з' => 'z',
			'и' => 'i',
			'й' => 'j',
			'к' => 'k',
			'л' => 'l',
			'м' => 'm',
			'н' => 'n',
			'о' => 'o',
			'п' => 'p',
			'р' => 'r',
			'с' => 's',
			'т' => 't',
			'у' => 'u',
			'ф' => 'f',
			'х' => 'h',
			'ц' => 'c',
			'ч' => 'ch',
			'ш' => 'sh',
			'щ' => 'sh',
			'ъ' => '',
			'ы' => 'y',
			'ь' => '',
			'э' => 'e',
			'ю' => 'yu',
			'я' => 'ya',
			'Є' => 'Ye',
			'І' => 'I',
			'Ї' => 'Yi',
			'Ґ' => 'G',
			'є' => 'ye',
			'і' => 'i',
			'ї' => 'yi',
			'ґ' => 'g',
			'Č' => 'C',
			'Ď' => 'D',
			'Ě' => 'E',
			'Ň' => 'N',
			'Ř' => 'R',
			'Š' => 'S',
			'Ť' => 'T',
			'Ů' => 'U',
			'Ž' => 'Z',
			'č' => 'c',
			'ď' => 'd',
			'ě' => 'e',
			'ň' => 'n',
			'ř' => 'r',
			'š' => 's',
			'ť' => 't',
			'ů' => 'u',
			'ž' => 'z',
			'Ą' => 'A',
			'Ć' => 'C',
			'Ę' => 'e',
			'Ł' => 'L',
			'Ń' => 'N',
			'Ó' => 'o',
			'Ś' => 'S',
			'Ź' => 'Z',
			'Ż' => 'Z',
			'ą' => 'a',
			'ć' => 'c',
			'ę' => 'e',
			'ł' => 'l',
			'ń' => 'n',
			'ó' => 'o',
			'ś' => 's',
			'ź' => 'z',
			'ż' => 'z',
			'Ā' => 'A',
			'Č' => 'C',
			'Ē' => 'E',
			'Ģ' => 'G',
			'Ī' => 'i',
			'Ķ' => 'k',
			'Ļ' => 'L',
			'Ņ' => 'N',
			'Š' => 'S',
			'Ū' => 'u',
			'Ž' => 'Z',
			'ā' => 'a',
			'č' => 'c',
			'ē' => 'e',
			'ģ' => 'g',
			'ī' => 'i',
			'ķ' => 'k',
			'ļ' => 'l',
			'ņ' => 'n',
			'š' => 's',
			'ū' => 'u',
			'ž' => 'z',
		);

		if (!empty($options['replacements']) && is_array($options['replacements'])) {
            $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
        }

        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }

        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
        $str = mb_substr($str, 0, $options['limit'] ?? mb_strlen($str, 'UTF-8'), 'UTF-8');
        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}
	public function iN_GetIPAddress() {
        if (!function_exists('validate_ip')) {
            function validate_ip($ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return true;
                }
                return false;
            }
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }

        if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
	public function iN_getHost($url) {
		$parseUrl = parse_url(trim($url));
		if (isset($parseUrl['host'])) {
			$host = $parseUrl['host'];
		} else {
			$path = explode('/', $parseUrl['path']);
			$host = $path[0];
		}
		return trim($host);
	}
	public function SlugPost($string) {
		$slug = $this->url_slugies($string, array(
			'delimiter' => '-',
			'limit' => 80,
			'lowercase' => true,
			'replacements' => array(
				'/\b(an)\b/i' => 'a',
				'/\b(example)\b/i' => 'Test',
			),
		));
		return $slug;
	}
	public function sanitize_output($buffer, $base_url) {
        // Ensure $buffer is a string to prevent deprecated warning in PHP 8.1+ when null is passed to preg_replace
        if (!is_string($buffer)) {
            $buffer = '';
        }

        // Define regular expressions to clean the HTML output
        $search = array(
            '/\>[^\S ]+/s',              // Remove whitespace characters (except space) after ">"
            '/[^\S ]+\</s',              // Remove whitespace characters (except space) before "<"
            '/(\s)+/s',                  // Replace multiple whitespace characters with a single space
            '/<!--(.|\s)*?-->/',         // Remove HTML comments
        );

        // Define replacement patterns corresponding to the above search patterns
        $replace = array(
            '>',
            '<',
            '\\1',
            '',
        );

        // Apply the regex replacements to the buffer
        $buffer = preg_replace($search, $replace, $buffer);

        // Convert mentions (e.g. @username) to clickable profile links
        $buffer = $this->iN_GetTheMentions($buffer, $base_url);

        // Convert hashtags (e.g. #hashtag) to clickable search links
        $buffer = $this->iN_MakeHashLink($buffer, $base_url);

        // Return the cleaned and formatted HTML output
        return $buffer;
    }
	public function iN_MakeHashLink($orimessage, $base_url) {
		$message = $orimessage;
		$regex = '/#([^`~!@$%^&*\#()\-+=\\|\/\.,<>?\'\":;{}\[\]* ]+)/i';
		$background_colors = array('#e53935', '#d81b60', '#8e24aa', '#5e35b1', '#3949ab', '#1e88e5', '#00acc1', '#6d4c41', '#546e7a');

		$rand_background = $background_colors[array_rand($background_colors)];
        $message = preg_replace($regex, '<a href="' . $base_url . 'hashtag/$1" class="hshCl" data-color="' . $rand_background . '">$0</a>', $message);
		return $message;
	}
    public function iN_GetTheMentions($content, $base_url) {
        if (preg_match_all("/\B@\K[\w-]+/", $content, $matches)) {
            $users = array_unique($matches[0] ?? []);
            if (!empty($users)) {
                $placeholders = implode(',', array_fill(0, count($users), '?'));
                $rows = DB::all("SELECT iuid, i_username, i_user_fullname FROM i_users WHERE i_username IN ($placeholders)", array_values($users));
                foreach ($rows as $row) {
                    $background_colors = array('deeppink', 'crimson', 'gold', 'magenta', 'darkorchid', 'limegreen', 'darkturquoise', 'dodgerblue', 'lightslategray');
                    $rand_background = $background_colors[array_rand($background_colors)];
                    $u = $row['i_username'];
                    $id = $row['iuid'];
                    $content = preg_replace("~\\B@{$u}\\b~", "<a href=\"{$base_url}{$u}\" class=\"mention_ show_card\" id=\"{$id}\" data-user=\"{$u}\" data-type=\"userCard\" style=\"color:{$rand_background}\">@{$u}</a>", $content);
                }
            }
        }
        return $content;
    }
	public function iN_hashtag($orimessage) {
		preg_match_all('/#+(\w+)/u', $orimessage, $matched_hashtag);
		$hashtag = '';
		if (!empty($matched_hashtag[0])) {
			foreach ($matched_hashtag[0] as $matched) {
				$hashtag .= preg_replace('/[^\p{L}0-9\s]+/u', '', $matched) . ',';
			}
		}
		return rtrim($hashtag, ',');
	}
	public function iN_Secure($string, $censored_words = 1, $br = true, $strip = 0) {
        if (!is_string($string)) {
            $string = (string)$string;
        }

        $string = trim($string);
        $string = $this->iN_cleanString($string);
        $string = htmlspecialchars($string, ENT_QUOTES);

        if ($br === true) {
            $string = str_replace(['\r\n', '\n\r', '\r', '\n'], ' <br>', $string);
        } else {
            $string = str_replace(['\r\n', '\n\r', '\r', '\n'], '', $string);
        }

        if ($strip == 1) {
            $string = stripslashes($string);
        }

        $string = str_replace('&amp;#', '&#', $string);
        $string = str_replace('&#039;', "'", $string);

        return $string;
    }
	public function iN_cleanString($string) {
		return $string = preg_replace("/&#?[a-z0-9]+;/i", "", $string);
	}
	/*Random*/
	public function random_code($length) {
		return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
	}
	function iN_BbcodeSecure($string) {
	    if (!is_string($string)) {
            $string = (string)$string;
        }
		$string = trim($string);
		$string = htmlspecialchars($string, ENT_QUOTES);
		$string = str_replace('\r\n', "[nl]", $string);
		$string = str_replace('\n\r', "[nl]", $string);
		$string = str_replace('\r', "[nl]", $string);
		$string = str_replace('\n', "[nl]", $string);
		$string = str_replace('&amp;#', '&#', $string);
		$string = strip_tags($string);
		$string = stripslashes($string);
		return $string;
	}
	public function iN_Decode($string) {
		return htmlspecialchars_decode($string);
	}
	public function iN_strip_unsafe($string, $img = false) {
		$unsafe = array(
			'/<iframe(.*?)<\/iframe>/is',
			'/<title(.*?)<\/title>/is',
			'/<pre(.*?)<\/pre>/is',
			'/<frame(.*?)<\/frame>/is',
			'/<frameset(.*?)<\/frameset>/is',
			'/<object(.*?)<\/object>/is',
			'/<script(.*?)<\/script>/is',
			'/<embed(.*?)<\/embed>/is',
			'/<applet(.*?)<\/applet>/is',
			'/<meta(.*?)>/is',
			'/<!doctype(.*?)>/is',
			'/<link(.*?)>/is',
			'/<body(.*?)>/is',
			'/<\/body>/is',
			'/<head(.*?)>/is',
			'/<\/head>/is',
			'/onload="(.*?)"/is',
			'/onunload="(.*?)"/is',
			'/<html(.*?)>/is',
			'/<\/html>/is');
		if ($img == true) {
			$unsafe[] = '/<img(.*?)>/is';
		}
		$string = preg_replace($unsafe, "", $string);

		return $string;
	}
	public function br2nl($st) {
		$breaks = array(
			"\r\n",
			"\r",
			"\n",
		);
		$st = str_replace($breaks, "", $st);
		$st_no_lb = preg_replace("/\r|\n/", "", $st);
		return preg_replace('/<br(\s+)?\/?>/i', "\r", $st_no_lb);
	}
	public function br2nlf($st) {
		$breaks = array(
			"\r\n",
			"\r",
			"\n",
		);
		$st = str_replace($breaks, "", $st);
		$st_no_lb = preg_replace("/\r|\n/", "", $st);
		$st = preg_replace('/<br(\s+)?\/?>/i', "\r", $st_no_lb);
		return str_replace('[nl]', "\r", $st);
	}
	public function isDate($string) {
		$matches = array();
		$pattern = '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/';
		if (!preg_match($pattern, $string, $matches)) {
			return false;
		}

		if (!checkdate($matches[2], $matches[1], $matches[3])) {
			return false;
		}

		return true;
	}
	public function iN_CorrectDateFormat($bdate) {
		$date = str_replace('/', '-', $bdate);
		if($date){
			$correct = date('Y-m-d', strtotime($date));
		}
		return $correct;
	}
	/*Update Who Can See Post Status*/
    public function iN_UpdatePostWhoCanSee($userID, $postID, $WhoCS) {
        if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckPostIDExist($postID) == '1') {
            if ($this->iN_CheckIsAdmin($userID) == 1) {
                DB::exec("UPDATE i_posts SET who_can_see = ? WHERE post_id = ?", [(string)$WhoCS, (int)$postID]);
            } else {
                DB::exec("UPDATE i_posts SET who_can_see = ? WHERE post_owner_id = ? AND post_id = ?", [(string)$WhoCS, (int)$userID, (int)$postID]);
            }
            return true;
        }
        return false;
    }
	/*Save Update Post Text*/
    public function iN_UpdatePost($userID, $postID, $editedText, $hashTags, $editSlug) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            if ($this->iN_CheckIsAdmin($userID) == 1) {
                DB::exec("UPDATE i_posts SET post_text = ?, url_slug = ?, hashtags = ? WHERE post_id = ?", [(string)$editedText, (string)$editSlug, (string)$hashTags, (int)$postID]);
            } else {
                DB::exec("UPDATE i_posts SET post_text = ?, url_slug = ?, hashtags = ? WHERE post_owner_id = ? AND post_id = ?", [(string)$editedText, (string)$editSlug, (string)$hashTags, (int)$userID, (int)$postID]);
            }
            return true;
        }
        return false;
    }
	/*Delete Post From Data*/
    public function iN_DeletePost($userID, $postID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            $getPostFileIDs = $this->iN_GetAllPostDetails($postID);
            $postFileIDs = isset($getPostFileIDs['post_file']) ? $getPostFileIDs['post_file'] : null;
            $s3 = DB::one("SELECT s3_status, was_status, ocean_status FROM i_configurations WHERE configuration_id = 1");
            $s3Status = $s3['s3_status'] ?? '0';
            $WasStatus = $s3['was_status'] ?? '0';
            $oceanStatus = $s3['ocean_status'] ?? '0';
            if ($postFileIDs && $s3Status != '1' && $oceanStatus != '1' && $WasStatus != '1') {
                $trimValue = rtrim($postFileIDs, ',');
                $explodeFiles = array_unique(explode(',', $trimValue));
                foreach ($explodeFiles as $explodeFile) {
                    $theFileID = $this->iN_GetUploadedFileDetails($explodeFile);
                    if (!$theFileID) { continue; }
                    $uploadedFileID = $theFileID['upload_id'];
                    $uploadedFilePath = $theFileID['uploaded_file_path'];
                    $uploadedTumbnailFilePath = $theFileID['upload_tumbnail_file_path'];
                    $uploadedFilePathX = $theFileID['uploaded_x_file_path'];
                    @unlink('../' . $uploadedFilePath);
                    @unlink('../' . $uploadedFilePathX);
                    @unlink('../' . $uploadedTumbnailFilePath);
                    DB::exec("DELETE FROM i_user_uploads WHERE upload_id = ? AND iuid_fk = ?", [(int)$uploadedFileID, (int)$userID]);
                }
            }
            if ($this->iN_CheckIsAdmin($userID) == 1) {
                DB::exec("DELETE FROM i_posts WHERE post_id = ?", [(int)$postID]);
                return true;
            } else {
                DB::exec("DELETE FROM i_posts WHERE post_id = ? AND post_owner_id = ?", [(int)$postID, (int)$userID]);
                return true;
            }
        }
        return false;
    }
    public function iN_DeletePostAdmin($userID, $postID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            $getPostFileIDs = $this->iN_GetAllPostDetails($postID);
            $postFileIDs = isset($getPostFileIDs['post_file']) ? $getPostFileIDs['post_file'] : null;
            $s3 = DB::one("SELECT s3_status, was_status, ocean_status FROM i_configurations WHERE configuration_id = 1");
            $s3Status = $s3['s3_status'] ?? '0';
            $WasStatus = $s3['was_status'] ?? '0';
            $oceanStatus = $s3['ocean_status'] ?? '0';
            if ($postFileIDs && $s3Status != '1' && $oceanStatus != '1' && $WasStatus != '1') {
                $trimValue = rtrim($postFileIDs, ',');
                $explodeFiles = array_unique(explode(',', $trimValue));
                foreach ($explodeFiles as $explodeFile) {
                    $theFileID = $this->iN_GetUploadedFileDetails($explodeFile);
                    if (!$theFileID) { continue; }
                    $uploadedFileID = $theFileID['upload_id'];
                    $uploadedFilePath = $theFileID['uploaded_file_path'];
                    $uploadedTumbnailFilePath = $theFileID['upload_tumbnail_file_path'];
                    $uploadedFilePathX = $theFileID['uploaded_x_file_path'];
                    @unlink('../' . $uploadedFilePath);
                    @unlink('../' . $uploadedFilePathX);
                    @unlink('../' . $uploadedTumbnailFilePath);
                    DB::exec("DELETE FROM i_user_uploads WHERE upload_id = ?", [(int)$uploadedFileID]);
                }
            }
            DB::exec("DELETE FROM i_posts WHERE post_id = ?", [(int)$postID]);
            return true;
        }
        return false;
    }
	/*Delete Post From Data if Storage Deleting*/
    public function iN_DeletePostFromDataifStorage($userID, $postID){
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            DB::exec("DELETE FROM i_posts WHERE post_id = ? AND post_owner_id = ?", [(int)$postID, (int)$userID]);
            return true;
        }
        return false;
    }

    public function iN_DeletePostFromDataifStorageAdmin($userID, $postID){
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            DB::exec("DELETE FROM i_posts WHERE post_id = ?", [(int)$postID]);
            return true;
        }
        return false;
    }
	public function htmlcode($orimessage) {
		$message = preg_replace("/\r\n|\r|\n/", ' ', $orimessage);
		$s = array("<", ">");
		$z = array("&lt;", "&gt;");
		$message = str_replace($s, $z, $message);
		$message = trim(str_replace("\\n", "<br/>", $message));
		$message = preg_replace('/(<br\s*\/?\s*>\s*)*(<br\s*\/?\s*>)/', '<br>', $message);

		return htmlspecialchars(stripslashes($message), ENT_QUOTES, "UTF-8");
	}
	/*Get All Post Details*/
    public function iN_GetSuggestedPostByUser($userID, $postID) {
        if ($this->iN_CheckUserExist($userID) == '1') {
            $rows = DB::all(
                "SELECT DISTINCT P.*,U.*
                 FROM i_posts P FORCE INDEX (ixForcePostOwner)
                 INNER JOIN i_users U FORCE INDEX (ixForceUser)
                   ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3') AND P.post_file IS NOT NULL AND P.post_file <> ''
                 WHERE P.post_owner_id = ? AND P.post_id <> ?
                 ORDER BY P.post_id DESC LIMIT 6",
                [(int)$userID, (int)$postID]
            );
            return !empty($rows) ? $rows : false;
        }
        return false;
    }
	/*Check Post Comment Status*/
    public function iN_CheckPostCommentStatus($postID) {
        $val = DB::col("SELECT comment_status FROM i_posts WHERE post_id = ?", [(int)$postID]);
        return $val !== false ? $val : null;
    }
	/*Check Post Pin Status*/
    public function iN_CheckPostPinStatus($postID) {
        $val = DB::col("SELECT post_pined FROM i_posts WHERE post_id = ?", [(int)$postID]);
        return $val !== false ? $val : null;
    }
	/*Check Post Reported Before*/
    public function iN_CheckPostReportedBefore($userID, $postID) {
        return (bool) DB::col("SELECT 1 FROM i_post_reports WHERE reported_post = ? AND iuid_fk = ? LIMIT 1", [(int)$postID, (int)$userID]);
    }
	/*Check Post Saved Before*/
    public function iN_CheckPostSavedBefore($userID, $postID) {
        return (bool) DB::col("SELECT 1 FROM i_saved_posts WHERE saved_post_id = ? AND iuid_fk = ? LIMIT 1", [(int)$postID, (int)$userID]);
    }
	/*Check Post OWNER status*/
    public function iN_CheckPostOwnerStatus($userID, $postID) {
        return (bool) DB::col("SELECT 1 FROM i_posts WHERE post_id = ? AND post_owner_id = ? LIMIT 1", [(int)$postID, (int)$userID]);
    }
	/*Update Post Comment Status*/
    public function iN_UpdatePostCommentStatus($userID, $postID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            $cStatus = ($this->iN_CheckPostCommentStatus($postID) == '1') ? '0' : '1';
            if ($this->iN_CheckIsAdmin($userID) == 1) {
                DB::exec("UPDATE i_posts SET comment_status = ? WHERE post_id = ?", [(string)$cStatus, (int)$postID]);
            } else {
                DB::exec("UPDATE i_posts SET comment_status = ? WHERE post_owner_id = ? AND post_id = ?", [(string)$cStatus, (int)$userID, (int)$postID]);
            }
            return $this->iN_CheckPostCommentStatus($postID);
        }
        return false;
    }
	/*Pin Post*/
    public function iN_UpdatePostPinedStatus($userID, $postID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            $pin_Status = ($this->iN_CheckPostPinStatus($postID) == '1') ? '0' : '1';
            if ($this->iN_CheckIsAdmin($userID) == 1) {
                DB::exec("UPDATE i_posts SET post_pined = ? WHERE post_id = ?", [(string)$pin_Status, (int)$postID]);
            } else {
                DB::exec("UPDATE i_posts SET post_pined = ? WHERE post_owner_id = ? AND post_id = ?", [(string)$pin_Status, (int)$userID, (int)$postID]);
            }
            return $this->iN_CheckPostPinStatus($postID);
        }
        return false;
    }
	/*Report Post*/
    public function iN_InsertReportedPost($userID, $postID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            $time = time();
            if ($this->iN_CheckPostReportedBefore($userID, $postID) == 1) {
                DB::exec("DELETE FROM i_post_reports WHERE reported_post = ? AND iuid_fk = ?", [(int)$postID, (int)$userID]);
                return 'un';
            } else {
                DB::exec("INSERT INTO i_post_reports(reported_post, iuid_fk, report_time) VALUES (?,?,?)", [(int)$postID, (int)$userID, $time]);
                return 'rep';
            }
        }
        return false;
    }
	/*Save Post in Saved List*/
    public function iN_SavePostInSavedList($userID, $postID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            $time = time();
            if ($this->iN_CheckPostSavedBefore($userID, $postID) == 1) {
                DB::exec("DELETE FROM i_saved_posts WHERE saved_post_id = ? AND iuid_fk = ?", [(int)$postID, (int)$userID]);
                return 'unsp';
            } else {
                DB::exec("INSERT INTO i_saved_posts(saved_post_id, iuid_fk, saved_time) VALUES (?,?,?)", [(int)$postID, (int)$userID, $time]);
                return 'svp';
            }
        }
        return false;
    }
	/*Check Sticker ID Exist*/
    public function iN_CheckStickerIDExist($stickerID) {
        return (bool) DB::col("SELECT 1 FROM i_stickers WHERE sticker_id = ? LIMIT 1", [(int)$stickerID]);
    }
	/*Insert New Comment*/
    public function iN_insertNewComment($userID, $postID, $comment, $stickerID, $gifUrl) {
        $get = $this->iN_GetAllPostDetails($postID);
        $checkUser_Blocked = $this->iN_CheckUserBlocked($get['post_owner_id'], $userID);
        $commenterBlockedPostOwner = $this->iN_CheckUserBlocked($userID, $get['post_owner_id']);
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1 && $checkUser_Blocked != 1 && $commenterBlockedPostOwner != 1) {
            $dataStickerUrl = '';
            $dataGifUrl = '';
            if ($stickerID) {
                if ($this->iN_CheckStickerIDExist($stickerID) == 1) {
                    $stickerURL = $this->iN_getSticker($stickerID);
                    $dataStickerUrl = $stickerURL['sticker_url'];
                } else { return false; }
            } else if ($gifUrl) {
                $dataGifUrl = $gifUrl;
                $dataStickerUrl = '';
            }
            $time = time();
            if ($this->iN_CheckIsAdmin($userID) == 1 || $this->iN_CheckPostCommentStatus($postID) == 1 || $this->iN_CheckPostOwnerStatus($userID, $postID) == 1) {
                DB::exec("INSERT INTO i_post_comments (comment_post_id_fk, comment_uid_fk, comment_time, comment, sticker_url, gif_url) VALUES (?,?,?,?,?,?)",
                    [(int)$postID, (int)$userID, $time, (string)$comment, (string)$dataStickerUrl, (string)$dataGifUrl]
                );
                $row = DB::one(
                    "SELECT C.com_id,C.comment_uid_fk,C.comment_post_id_fk,C.comment,C.comment_time,C.comment_file,C.sticker_url,C.gif_url,
                            U.iuid,U.i_username,U.i_user_fullname,U.user_avatar,U.user_gender,U.last_login_time,U.user_verified_status,U.i_user_email
                     FROM i_post_comments C FORCE INDEX(ixComment)
                     INNER JOIN i_users U FORCE INDEX(ixForceUser) ON C.comment_uid_fk = U.iuid AND U.uStatus IN('1','3')
                     WHERE C.comment_post_id_fk = ? AND C.comment_uid_fk = ? ORDER BY C.com_id DESC LIMIT 1",
                    [(int)$postID, (int)$userID]
                );
                return $row ?: false;
            }
            return false;
        }
        return false;
    }
	/*Like Post Comment*/
    public function iN_LikePostComment($userID, $postID, $commentID) {
        $time = time();
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            if ($this->iN_CheckCommentLikedBefore($userID, $postID, $commentID) == 1) {
                DB::exec("DELETE FROM i_post_comment_likes WHERE c_like_post_id = ? AND c_like_iuid_fk = ? AND c_like_comment_id = ?", [(int)$postID, (int)$userID, (int)$commentID]);
                return false;
            } else {
                DB::exec("INSERT INTO i_post_comment_likes (c_like_post_id,c_like_iuid_fk,c_like_comment_id,c_like_time) VALUES (?,?,?,?)", [(int)$postID,(int)$userID,(int)$commentID,$time]);
                return true;
            }
        }
        return false;
    }
	/*Get Comment Owner ID From Liked Post ID*/
    public function iN_GetUserIDFromLikedPostID($commentID) {
        return DB::one("SELECT * FROM i_post_comments WHERE com_id = ? LIMIT 1", [(int)$commentID]);
    }
	/*Comment Like Count*/
    public function iN_TotalCommentLiked($commentID) {
        $val = DB::col("SELECT COUNT(*) FROM i_post_comment_likes WHERE c_like_comment_id = ?", [(int)$commentID]);
        return (int)$val;
    }
	/*Comment Like Count*/
    public function iN_TotalPostLiked($postID) {
        $val = DB::col("SELECT COUNT(*) FROM i_post_likes WHERE post_id_fk = ?", [(int)$postID]);
        return (int)$val;
    }
	/*Check Notification Inserted Before For Comment For Same User*/
public function iN_CheckNotificationLikeInsertedBeforeForComment($userID, $commentID, $postID) {
        return (bool) DB::col("SELECT 1 FROM i_user_notifications WHERE not_iuid = ? AND not_post_id = ? AND not_comment_id = ? AND not_not_type = 'commentLike' LIMIT 1", [(int)$userID,(int)$postID,(int)$commentID]);
}
	/*Check Notification Inserted Before For Comment For Same User*/
public function iN_CheckNotificationInsertedBeforePostLike($userID, $postID) {
        return (bool) DB::col("SELECT 1 FROM i_user_notifications WHERE not_iuid = ? AND not_post_id = ? AND not_not_type = 'postLike' LIMIT 1", [(int)$userID,(int)$postID]);
}
	/*Check User Liked Comment Before*/
public function iN_CheckUserLikedCommentBefore($userID, $commentID) {
        return (bool) DB::col("SELECT 1 FROM i_post_comment_likes WHERE c_like_iuid_fk = ? AND c_like_comment_id = ? LIMIT 1", [(int)$userID,(int)$commentID]);
}
	/*Get Comment Details*/
    public function iN_GetCommentDetails($commendID) {
        return DB::one("SELECT * FROM i_post_comments WHERE com_id = ? LIMIT 1", [(int)$commendID]);
    }
	/*Insert Comment Notification*/
    public function iN_insertCommentLikeNotification($userID, $postID, $commentID) {
		$postData = $this->iN_GetAllPostDetails($postID);
		$postFile = isset($postData['post_file']) ? $postData['post_file'] : NULL;
		$postOwnerID = isset($postData['post_owner_id']) ? $postData['post_owner_id'] : NULL;
		$comData = $this->iN_GetCommentDetails($commentID);
		$commentOwnerID = isset($comData['comment_uid_fk']) ? $comData['comment_uid_fk'] : NULL;
		if ($postFile) {
			$notType = 'image';
		} else {
			$notType = 'text';
		}
		$time = time();
		if ($this->iN_CheckNotificationLikeInsertedBeforeForComment($userID, $commentID, $postID) == 1) {
            DB::exec("DELETE FROM i_user_notifications WHERE not_iuid = ? AND not_post_id = ? AND not_comment_id = ?", [(int)$userID, (int)$postID, (int)$commentID]);
            return false;
        } else if ($this->iN_CheckUserLikedCommentBefore($commentOwnerID, $commentID) != '1') {
            DB::exec("INSERT INTO i_user_notifications (not_iuid, not_post_id, not_comment_id, not_own_iuid, not_type, not_not_type, not_time) VALUES (?,?,?,?,?,?,?)",
                [(int)$userID,(int)$postID,(int)$commentID,(int)$commentOwnerID,(string)$notType,'commentLike',$time]
            );
            DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE iuid = ?", [(int)$commentOwnerID]);
            return true;
        }
    }
	/*Insert Comment Notification*/
    public function iN_insertPostLikeNotification($userID, $postID) {
		$postData = $this->iN_GetAllPostDetails($postID);
		$postFile = isset($postData['post_file']) ? $postData['post_file'] : NULL;
		$postOwnerID = isset($postData['post_owner_id']) ? $postData['post_owner_id'] : NULL;
		if ($postFile) {
			$notType = 'image';
		} else {
			$notType = 'text';
		}
		$time = time();
		if ($this->iN_CheckNotificationInsertedBeforePostLike($userID, $postID) == 1) {
            DB::exec("DELETE FROM i_user_notifications WHERE not_iuid = ? AND not_post_id = ?", [(int)$userID, (int)$postID]);
            return false;
        } else if ($userID !== $postOwnerID) {
            DB::exec("INSERT INTO i_user_notifications (not_iuid, not_post_id, not_own_iuid, not_type, not_not_type, not_time) VALUES (?,?,?,?,?,?)",
                [(int)$userID,(int)$postID,(int)$postOwnerID,(string)$notType,'postLike',$time]
            );
            DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE iuid = ?", [(int)$postOwnerID]);
            return true;
        }
    }
	/*Insert Notification for Commented*/
    public function iN_InsertNotificationForCommented($userID, $postID) {
		$postData = $this->iN_GetAllPostDetails($postID);
		$postFile = isset($postData['post_file']) ? $postData['post_file'] : NULL;
		$postOwnerID = isset($postData['post_owner_id']) ? $postData['post_owner_id'] : NULL;
		if ($postFile) {
			$notType = 'image';
		} else {
			$notType = 'text';
		}
		$time = time();
		DB::exec("INSERT INTO i_user_notifications (not_iuid, not_post_id, not_own_iuid, not_type, not_not_type, not_time) VALUES (?,?,?,?,?,?)",
            [(int)$userID,(int)$postID,(int)$postOwnerID,(string)$notType,'commented',$time]
        );
        DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE iuid = ?", [(int)$postOwnerID]);
        return true;
    }
	/*Insert Notification for Follow*/
    public function iN_InsertNotificationForFollow($userID, $userTwo) {
		$time = time();
		DB::exec("INSERT INTO i_user_notifications(not_iuid, not_own_iuid, not_not_type, not_time) VALUES (?,?,?,?)", [(int)$userID,(int)$userTwo,'follow',$time]);
        DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE iuid = ?", [(int)$userTwo]);
        return true;
    }
	/*Insert Notification for Follow*/
    public function iN_RemoveNotificationForFollow($userID, $userTwo) {
        $deleted = DB::exec("DELETE FROM i_user_notifications WHERE not_iuid = ? AND not_own_iuid = ? AND not_not_type = 'follow'", [(int)$userID,(int)$userTwo]);
        return $deleted > 0;
    }
	/*Insert Notification for Follow*/
    public function iN_InsertNotificationForSubscribe($userID, $userTwo) {
		$time = time();
		DB::exec("INSERT INTO i_user_notifications(not_iuid, not_own_iuid, not_not_type, not_time) VALUES (?,?,?,?)", [(int)$userID,(int)$userTwo,'subscribe',$time]);
        DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE iuid = ?", [(int)$userTwo]);
        return true;
    }
	/*Insert Notification for Follow*/
    public function iN_RemoveNotificationForSubscribe($userID, $userTwo) {
        $deleted = DB::exec("DELETE FROM i_user_notifications WHERE not_iuid = ? AND not_own_iuid = ? AND not_not_type = 'subscribe'", [(int)$userID,(int)$userTwo]);
        return $deleted > 0;
    }
	/*Insert Notification for Verification Decision (approved/declined)*/
    public function iN_InsertNotificationForVerificationDecision($fromUserID, $toUserID, $approved) {
        $time = time();
        $type = $approved ? 'verification_approved' : 'verification_declined';
        DB::exec(
            "INSERT INTO i_user_notifications (not_iuid, not_own_iuid, not_not_type, not_time) VALUES (?,?,?,?)",
            [(int)$fromUserID, (int)$toUserID, (string)$type, (int)$time]
        );
        DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE iuid = ?", [(int)$toUserID]);
        return true;
    }
	/*Check Comment ID EXISTs*/
    public function iN_CheckCommentIDExist($commentID, $userID) {
        return (bool) DB::col("SELECT 1 FROM i_post_comments WHERE com_id = ? AND comment_uid_fk = ? LIMIT 1", [(int)$commentID,(int)$userID]);
    }
	/*Check Comment ID EXISTs*/
    public function iN_CheckCommentIDExistUniq($commentID) {
        return (bool) DB::col("SELECT 1 FROM i_post_comments WHERE com_id = ? LIMIT 1", [(int)$commentID]);
    }
	/*Delete Comment*/
public function iN_DeleteComment($userID, $commentID, $postID) {
        if ($this->iN_CheckCommentIDExist($commentID, $userID) == '1' && $this->iN_CheckPostIDExist($postID) == 1) {
            try {
                DB::begin();
                DB::exec("DELETE FROM i_post_comments WHERE comment_uid_fk = ? AND com_id = ?", [(int)$userID,(int)$commentID]);
                DB::exec("DELETE FROM i_user_notifications WHERE not_iuid = ? AND not_post_id = ? AND not_not_type = 'commented'", [(int)$userID,(int)$postID]);
                DB::exec("DELETE FROM i_post_comment_likes WHERE c_like_comment_id = ? AND c_like_post_id = ?", [(int)$commentID,(int)$postID]);
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        } else if ($this->iN_CheckCommentIDExistUniq($commentID) == '1' && $this->iN_CheckPostIDExist($postID) == 1) {
            if ($this->iN_CheckIsAdmin($userID) == 1) {
                try {
                    DB::begin();
                    $row = DB::one("SELECT comment_uid_fk FROM i_post_comments WHERE com_id = ?", [(int)$commentID]);
                    DB::exec("DELETE FROM i_post_comments WHERE com_id = ?", [(int)$commentID]);
                    if ($row) { DB::exec("DELETE FROM i_user_notifications WHERE not_iuid = ? AND not_post_id = ? AND not_not_type = 'commented'", [(int)$row['comment_uid_fk'], (int)$postID]); }
                    DB::exec("DELETE FROM i_post_comment_likes WHERE c_like_comment_id = ? AND c_like_post_id = ?", [(int)$commentID,(int)$postID]);
                    DB::commit();
                    return true;
                } catch (Throwable $e) { DB::rollBack(); return false; }
            }
        } else { return false; }
}
	/*Check Post Reported Before*/
	public function iN_CheckCommentReportedBefore($userID, $commentID) {
		return (bool) DB::col("SELECT 1 FROM i_comment_reports WHERE reported_comment = ? AND iuid_fk = ? LIMIT 1", [(int)$commentID,(int)$userID]);
	}
	/*Report Comment*/
public function iN_InsertReportedComment($userID, $commentID, $postID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1 && $this->iN_CheckCommentIDExistUniq($commentID)) {
            $time = time();
            if ($this->iN_CheckCommentReportedBefore($userID, $commentID) == 1) {
                DB::exec("DELETE FROM i_comment_reports WHERE reported_comment = ? AND iuid_fk = ?", [(int)$commentID,(int)$userID]);
                return 'un';
            } else {
                DB::exec("INSERT INTO i_comment_reports (reported_comment, iuid_fk, report_time, comment_post_id_fk) VALUES (?,?,?,?)", [(int)$commentID,(int)$userID,$time,(int)$postID]);
                return 'rep';
            }
        } else {return false;}
}
	/*Get Comment*/
public function iN_GetCommentFromID($userID, $commentID, $postID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckCommentIDExistUniq($commentID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            if ($this->iN_CheckIsAdmin($userID) == 1) {
                return DB::one("SELECT * FROM i_post_comments WHERE com_id = ?", [(int)$commentID]);
            } else {
                return DB::one("SELECT * FROM i_post_comments WHERE com_id = ? AND comment_uid_fk = ?", [(int)$commentID,(int)$userID]);
            }
        } else { return false; }
}
	/*Save Update Comment Text*/
public function iN_UpdateComment($userID, $postID, $commentID, $editedText) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1 && $this->iN_CheckCommentIDExistUniq($commentID) == 1) {
            try {
                if ($this->iN_CheckIsAdmin($userID) == 1) {
                    DB::exec("UPDATE i_post_comments SET comment = ? WHERE com_id = ?", [(string)$editedText,(int)$commentID]);
                } else {
                    DB::exec("UPDATE i_post_comments SET comment = ? WHERE com_id = ? AND comment_uid_fk = ?", [(string)$editedText,(int)$commentID,(int)$userID]);
                }
                return true;
            } catch (Throwable $e) {
                return false;
            }
        } else { return false; }
}
	/*Get Stickers From Data*/
    public function iN_GetActiveStickers() {
        $rows = DB::all("SELECT * FROM i_stickers WHERE sticker_status = '1'");
        return !empty($rows) ? $rows : null;
    }
	/*Get Sticker By ID*/
    public function iN_getSticker($stickerID) {
        $row = DB::one("SELECT sticker_id, sticker_url FROM i_stickers WHERE sticker_id = ? AND sticker_status = '1' LIMIT 1", [(int)$stickerID]);
        return $row ?: false;
    }
	/*Calculate Birthday*/
	public function iN_CalculateUserAge($birthDate) {
		$birthDate = explode("/", $birthDate);
		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
		return $age;
	}
	/*Birthday Validator*/
	public function checkDateFormat($date) {
		return preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/", $date);
	}
	/*Update User Last Seen*/
    public function iN_UpdateLastSeen($userID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $time = time();

            // Optimization: only update if more than 60 seconds passed since last update
            // This reduces DB load significantly (10-100x fewer UPDATE queries)
            $lastUpdate = isset($_SESSION['last_seen_update']) ? $_SESSION['last_seen_update'] : 0;

            if (($time - $lastUpdate) >= 60) {
                DB::exec("UPDATE i_users SET last_login_time = ? WHERE iuid = ?", [$time, (int)$userID]);
                $_SESSION['last_seen_update'] = $time;
            }
        }
    }
	/*Get Total Posts*/
    public function iN_TotalPosts($userID) {
        $val = DB::col("SELECT COUNT(*) FROM i_posts WHERE post_owner_id = ?", [(int)$userID]);
        return (int)$val;
    }

	/**
	 * OPTIMIZATION: Get all profile statistics in ONE query
	 * Returns: total posts, images, videos, reels, audios, products, following, followers, subscribers
	 * Replaces 9 separate DB queries with 1 query using subqueries
	 */
	public function iN_GetProfileStats($userID) {
		$userID = (int)$userID;

		$sql = "SELECT
			(SELECT COUNT(*) FROM i_posts WHERE post_owner_id = ?) as total_posts,
			(SELECT COUNT(*) FROM i_posts WHERE post_owner_id = ? AND post_type = 'reels') as total_reels,
			(SELECT COUNT(*) FROM i_user_uploads WHERE iuid_fk = ? AND upload_status = '1' AND upload_type = 'wall' AND uploaded_file_ext IN('gif','GIF','jpg','jpeg','JPEG','JPG','PNG','png')) as total_images,
			(SELECT COUNT(*) FROM i_user_uploads WHERE iuid_fk = ? AND upload_status = '1' AND upload_type = 'wall' AND uploaded_file_ext IN('mp4','MP4')) as total_videos,
			(SELECT COUNT(*) FROM i_user_uploads WHERE iuid_fk = ? AND upload_status = '1' AND upload_type = 'wall' AND uploaded_file_ext IN('mp3','MP3')) as total_audios,
			(SELECT COUNT(*) FROM i_user_product_posts WHERE iuid_fk = ?) as total_products,
			(SELECT COUNT(*) FROM i_friends WHERE fr_one = ? AND fr_status = 'flwr') as total_following,
			(SELECT COUNT(*) FROM i_friends WHERE fr_two = ? AND fr_status = 'flwr') as total_followers,
			(SELECT COUNT(*) FROM i_user_subscriptions WHERE subscribed_iuid_fk = ? AND status = 'active') as total_subscribers";

		$params = array_fill(0, 9, $userID);
		$stats = DB::one($sql, $params);

		return $stats ?: [
			'total_posts' => 0,
			'total_reels' => 0,
			'total_images' => 0,
			'total_videos' => 0,
			'total_audios' => 0,
			'total_products' => 0,
			'total_following' => 0,
			'total_followers' => 0,
			'total_subscribers' => 0
		];
	}

	/**
	 * OPTIMIZATION: Get all relationship data between two users in ONE query
	 * Returns: friendship status, subscription type, conversation ID, is_creator, block status
	 * Replaces 5-6 separate DB queries with 1 query using LEFT JOINs
	 */
	public function iN_GetProfileRelationships($currentUserID, $profileUserID) {
		$currentUserID = (int)$currentUserID;
		$profileUserID = (int)$profileUserID;

		// For guests, return minimal data
		if ($currentUserID === 0) {
			return [
				'friendship_status' => null,
				'subscription_type' => null,
				'conversation_id' => null,
				'is_creator' => false,
				'blocked_by_me' => false,
				'blocked_by_them' => false,
				'block_type' => null
			];
		}

		$sql = "SELECT
			f.fr_status as friendship_status,
			s.payment_method as subscription_type,
			c.chat_id as conversation_id,
			(SELECT 1 FROM i_users WHERE iuid = ? AND certification_status = '2' AND validation_status = '2' AND condition_status = '2' AND fees_status = '2' AND payout_status = '2' LIMIT 1) as is_creator,
			b1.block_type as blocked_by_me_type,
			b2.block_type as blocked_by_them_type
		FROM (SELECT ? as dummy_id) dummy
		LEFT JOIN i_friends f ON (f.fr_one = ? AND f.fr_two = ?)
		LEFT JOIN i_user_subscriptions s ON (s.iuid_fk = ? AND s.subscribed_iuid_fk = ?)
		LEFT JOIN i_chat_users c ON ((c.user_one = ? AND c.user_two = ?) OR (c.user_one = ? AND c.user_two = ?))
		LEFT JOIN i_user_blocks b1 ON (b1.blocker_iuid = ? AND b1.blocked_iuid = ?)
		LEFT JOIN i_user_blocks b2 ON (b2.blocker_iuid = ? AND b2.blocked_iuid = ?)
		LIMIT 1";

		$result = DB::one($sql, [
			$profileUserID, // is_creator check
			1, // dummy_id
			$currentUserID, $profileUserID, // friendship
			$currentUserID, $profileUserID, // subscription
			$currentUserID, $profileUserID, $profileUserID, $currentUserID, // conversation (both directions)
			$currentUserID, $profileUserID, // blocked by me
			$profileUserID, $currentUserID  // blocked by them
		]);

		return [
			'friendship_status' => $result['friendship_status'] ?? null,
			'subscription_type' => $result['subscription_type'] ?? null,
			'conversation_id' => $result['conversation_id'] ?? null,
			'is_creator' => (bool)($result['is_creator'] ?? false),
			'blocked_by_me' => !empty($result['blocked_by_me_type']),
			'blocked_by_them' => !empty($result['blocked_by_them_type']),
			'block_type' => $result['blocked_by_me_type'] ?: $result['blocked_by_them_type'] ?: null
		];
	}
	/*Get Total Posts*/
public function iN_TotalImagePosts($userID) {
        $val = DB::col("SELECT COUNT(*) FROM i_user_uploads WHERE iuid_fk = ? AND upload_status = '1' AND upload_type = 'wall' AND uploaded_file_ext IN('gif','GIF','jpg','jpeg','JPEG','JPG','PNG','png')", [(int)$userID]);
        return (int)$val;
}
	/*Get Total Posts*/
public function iN_TotalVideoPosts($userID) {
        $val = DB::col("SELECT COUNT(*) FROM i_user_uploads WHERE iuid_fk = ? AND upload_status = '1' AND upload_type = 'wall' AND uploaded_file_ext IN('mp4','MP4')", [(int)$userID]);
        return (int)$val;
}
    /*Get Total Reels Posts*/
public function iN_TotalReelsPosts($userID) {
        $val = DB::col("SELECT COUNT(*) FROM i_posts WHERE post_owner_id = ? AND post_type = 'reels'", [(int)$userID]);
        return (int)$val;
}
    /*Get Total Posts*/
public function iN_TotalAudioPosts($userID) {
        $val = DB::col("SELECT COUNT(*) FROM i_user_uploads WHERE iuid_fk = ? AND upload_status = '1' AND upload_type = 'wall' AND uploaded_file_ext IN('mp3','MP3')", [(int)$userID]);
        return (int)$val;
}
	/*Check user is in FLWR*/
public function iN_CheckUserIsInFLWR($userID, $uID) {
        return (bool) DB::col("SELECT 1 FROM i_friends WHERE fr_one = ? AND fr_two = ? AND fr_status = 'flwr' LIMIT 1", [(int)$userID,(int)$uID]);
}
	/*Check user is in FLWR*/
public function iN_CheckUserIsInSubscriber($userID, $uID) {
        return (bool) DB::col("SELECT 1 FROM i_friends WHERE fr_one = ? AND fr_two = ? AND fr_status = 'subscriber' LIMIT 1", [(int)$userID,(int)$uID]);
}
	/*UnSubscribe User*/
public function iN_UnSubscriberUser($userID, $uID,$unSubscribeStyle) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($uID) == 1) {
            if($unSubscribeStyle == 'no'){
                DB::exec("UPDATE i_friends SET fr_status = 'flwr' WHERE fr_one = ? AND fr_two = ?", [(int)$userID,(int)$uID]);
                return true;
            }
            if ($this->iN_CheckUserIsInSubscriber($userID, $uID) == '1') {
                DB::exec("DELETE FROM i_friends WHERE fr_one = ? AND fr_two = ?", [(int)$userID,(int)$uID]);
                return true;
            }
        } else { return false; }
}
	/*Insert New Following List*/
public function iN_insertNewFollow($userID, $uID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($uID) == 1) {
            $time = time();
            if ($this->iN_CheckUserIsInFLWR($userID, $uID) == '1') {
                DB::exec("DELETE FROM i_friends WHERE fr_one = ? AND fr_two = ? AND fr_status = 'flwr'", [(int)$userID,(int)$uID]);
                $this->iN_DeleteFollowActivity($userID, $uID);
                return 'unflw';
            } else {
                DB::exec("INSERT INTO i_friends (fr_one, fr_two, fr_status, fr_time) VALUES (?,?, 'flwr', ?)", [(int)$userID,(int)$uID,$time]);
                $this->iN_InsertFollowActivity($userID, 'userFollow', $uID, $time);
                return 'flw';
            }
        } else { return false; }
}
	/*Check User Blocked Before*/
public function iN_CheckUserBlocked($userID, $uID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($uID) == 1) {
            return (bool) DB::col("SELECT 1 FROM i_user_blocks WHERE blocker_iuid = ? AND blocked_iuid = ? LIMIT 1", [(int)$userID,(int)$uID]);
        }
}
	/*Check User Blocked User Profile*/
public function iN_CheckUserBlockedVisitor($userID, $uID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($uID) == 1) {
            return (bool) DB::col("SELECT 1 FROM i_user_blocks WHERE blocker_iuid = ? AND blocked_iuid = ? LIMIT 1", [(int)$userID,(int)$uID]);
        }
}
	/*Get User Block Type*/
public function iN_GetUserBlockedType($userID, $uID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($uID) == 1 && $this->iN_CheckUserBlocked($userID, $uID) == 1) {
            $row = DB::one("SELECT block_type FROM i_user_blocks WHERE blocker_iuid = ? AND blocked_iuid = ? LIMIT 1", [(int)$userID,(int)$uID]);
            return $row ? $row['block_type'] : false;
        }
}
	/*Insert User in Blocked List*/
public function iN_InsertBlockList($userID, $uID, $blockType) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($uID) == 1) {
            $time = time();
            if ($this->iN_CheckUserBlocked($userID, $uID) == 1) {
                DB::exec("DELETE FROM i_user_blocks WHERE blocker_iuid = ? AND blocked_iuid = ?", [(int)$userID,(int)$uID]);
                return 'bRemoved';
            } else {
                DB::exec("INSERT INTO i_user_blocks (blocker_iuid, blocked_iuid, block_type, blocked_time) VALUES (?,?,?,?)", [(int)$userID,(int)$uID,(string)$blockType,$time]);
                return 'bAdded';
            }
        } else { return false; }
}
	/*User Subscriptions OFFERS*/
public function iN_UserSusbscriptionOffers($userID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $rows = DB::all("SELECT * FROM i_user_subscribe_plans WHERE iuid_fk = ? AND plan_status = '1'", [(int)$userID]);
            return !empty($rows) ? $rows : null;
        }
}
	/*Check Plan Exist*/
public function iN_CheckPlanExist($planID, $userID) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            $exists = (bool) DB::col("SELECT 1 FROM i_user_subscribe_plans WHERE iuid_fk = ? AND plan_id = ? AND plan_status = '1' LIMIT 1", [(int)$userID, (int)$planID]);
            if ($exists) {
                return DB::one("SELECT * FROM i_user_subscribe_plans WHERE iuid_fk = ? AND plan_id = ? LIMIT 1", [(int)$userID, (int)$planID]);
            }
            return false;
        } else {
            return false;
        }
}
	/*Insert User Subscription*/
public function iN_InsertUserSubscription($userID, $subscribedUserID, $planType, $subscriberName, $subscrID, $custID, $planIDs, $planAmount, $adminEarning, $userNetEarning, $planCurrency, $planinterval, $planIntervalCount, $subscriberEmail, $plancreated, $current_period_start, $current_period_end, $planStatus) {
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($subscribedUserID) == 1) {
            DB::exec(
                "INSERT INTO i_user_subscriptions (iuid_fk, subscribed_iuid_fk, subscriber_name, payment_method, payment_subscription_id, customer_id, plan_id, plan_amount, admin_earning, user_net_earning, plan_amount_currency, plan_interval, plan_interval_count, payer_email, created, plan_period_start, plan_period_end, status)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [
                    (int)$userID, (int)$subscribedUserID, (string)$subscriberName, (string)$planType, (string)$subscrID, (string)$custID,
                    (string)$planIDs, (string)$planAmount, (string)$adminEarning, (string)$userNetEarning, (string)$planCurrency,
                    (string)$planinterval, (string)$planIntervalCount, (string)$subscriberEmail, (string)$plancreated,
                    (string)$current_period_start, (string)$current_period_end, (string)$planStatus
                ]
            );
            $time = time();
            $isFollower = (bool) DB::col("SELECT 1 FROM i_friends WHERE fr_one = ? AND fr_two = ? AND fr_status = 'flwr' LIMIT 1", [(int)$userID, (int)$subscribedUserID]);
            if ($isFollower) {
                DB::exec("UPDATE i_friends SET fr_status = 'subscriber', fr_time = ? WHERE fr_one = ? AND fr_two = ?", [$time, (int)$userID, (int)$subscribedUserID]);
            } else if (!$this->iN_CheckUserIsInSubscriber($userID, $subscribedUserID)) {
                DB::exec("INSERT INTO i_friends (fr_one, fr_two, fr_status, fr_time) VALUES (?,?, 'subscriber', ?)", [(int)$userID, (int)$subscribedUserID, $time]);
            }
            return true;
        }
	}
	/*Get Subscribe ID*/
public function iN_GetSubscribeID($userID, $uID) {
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($uID) == 1) {
            return DB::one("SELECT * FROM i_user_subscriptions WHERE iuid_fk = ? AND subscribed_iuid_fk = ? LIMIT 1", [(int)$userID, (int)$uID]);
        } else {return false;}
	}
	/*Update Subscription Status*/
public function iN_UpdateSubscriptionStatus($subscriptionID) {
        DB::exec("UPDATE i_user_subscriptions SET status = 'inactive', in_status = '1', finished = '1' WHERE subscription_id = ?", [(int)$subscriptionID]);

	}
public function iN_AllUserProfilePosts($uid, $lastPostID, $showingPost) {
		$uid = (int)$uid; $showingPosts = (int)$showingPost; $params = [$uid]; $w = '';
		if (!empty($lastPostID)) { $w = ' AND P.post_id < ?'; $params[] = (int)$lastPostID; }

		// OPTIMIZED: Added LEFT JOINs for likes count, comments count
		// This eliminates N+1 queries in the display loop
		$sql = "SELECT DISTINCT P.*, U.*,
				IFNULL(likes.total_likes, 0) AS total_likes,
				IFNULL(comments.total_comments, 0) AS total_comments
		FROM i_friends F
			INNER JOIN i_posts P ON P.post_owner_id = F.fr_two
			INNER JOIN i_users U ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3') AND F.fr_status IN('me', 'flwr', 'subscriber')
			LEFT JOIN (
				SELECT post_id_fk, COUNT(*) AS total_likes
				FROM i_post_likes
				GROUP BY post_id_fk
			) likes ON P.post_id = likes.post_id_fk
			LEFT JOIN (
				SELECT comment_post_id_fk, COUNT(*) AS total_comments
				FROM i_post_comments
				GROUP BY comment_post_id_fk
			) comments ON P.post_id = comments.comment_post_id_fk
		WHERE P.post_owner_id = ? AND P.post_pined = '0' $w
		ORDER BY P.post_id DESC LIMIT $showingPosts";

		$rows = DB::all($sql, $params);
		return !empty($rows) ? $rows : null;
	}
	/*Insert  A New Verification Request*/
public function iN_InsertNewVerificationRequest($userID, $cardIDPhoto, $Photo) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            $time = time();
            DB::exec("INSERT INTO i_verification_requests (iuid_fk, id_card, photo_of_card, request_status, request_time) VALUES (?,?,?,?,?)", [(int)$userID, (string)$Photo, (string)$cardIDPhoto, '0', $time]);
            DB::exec("UPDATE i_users SET certification_status = '1' WHERE iuid = ?", [(int)$userID]);
            return true;
        } else {
            return false;
        }
	}
	/*Accept Conditions Button by Clicking Next button*/
public function iN_AcceptConditions($userID) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("UPDATE i_users SET certification_status = '2', validation_status = '1' WHERE iuid = ?", [(int)$userID]);
            return true;
        }
	}
	/*Check user Set Subscription Fees Before*/
	public function iN_CheckUserSetSubscriptionFeesBefore($userID, $plan) {
		if ($this->iN_CheckUserExist($userID) == '1') {
            return (bool) DB::col("SELECT 1 FROM i_user_subscribe_plans WHERE iuid_fk = ? AND plan_type = ? LIMIT 1", [(int)$userID, (string)$plan]);
        }
	}
	/*Insert Subscription Weekly*/
	public function iN_InsertWeeklySubscriptionAmountAndStatus($userID, $SubWeekAmount, $weeklySubStatus) {
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserSetSubscriptionFeesBefore($userID, 'weekly') == '0') {
			$time = time();
			DB::exec("INSERT INTO i_user_subscribe_plans (iuid_fk, amount, plan_type, plan_created_time, plan_status) VALUES (?,?,?,?,?)", [(int)$userID, (string)$SubWeekAmount, 'weekly', $time, (string)$weeklySubStatus]);
			return true;
		} else {return false;}
	}
	/*Insert Subscription Monthly*/
	public function iN_InsertMonthlySubscriptionAmountAndStatus($userID, $SubMonthAmount, $monthlySubStatus) {
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserSetSubscriptionFeesBefore($userID, 'monthly') == '0') {
			$time = time();
			DB::exec("INSERT INTO i_user_subscribe_plans (iuid_fk, amount, plan_type, plan_created_time, plan_status) VALUES (?,?,?,?,?)", [(int)$userID, (string)$SubMonthAmount, 'monthly', $time, (string)$monthlySubStatus]);
			return true;
		} else {return false;}
	}
	/*Insert Subscription Yearly*/
	public function iN_InsertYearlySubscriptionAmountAndStatus($userID, $SubYearAmount, $yearlySubStatus) {
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserSetSubscriptionFeesBefore($userID, 'yearly') == '0') {
			$time = time();
			DB::exec("INSERT INTO i_user_subscribe_plans (iuid_fk, amount, plan_type, plan_created_time, plan_status) VALUES (?,?,?,?,?)", [(int)$userID, (string)$SubYearAmount, 'yearly', $time, (string)$yearlySubStatus]);
			return true;
		} else {return false;}
	}
	/*Update Fee Status From Users Table*/
public function iN_UpdateUserFeeStatus($userID) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("UPDATE i_users SET validation_status = '2', condition_status = '2', fees_status = '2' WHERE iuid = ?", [(int)$userID]);
            return true;
        }
	}
	/*Insert Payout Settings*/
public function iN_SetPayout($userID, $paypalEmail, $bankAccount, $defaultMethod) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("UPDATE i_users SET payout_method = ?, payout_status = '2', user_verified_status = '1', paypal_email = ?, bank_account = ? WHERE iuid = ?",
                [(string)$defaultMethod, (string)$paypalEmail, (string)$bankAccount, (int)$userID]
            );
            return true;
        } else {
            return false;
        }
	}
	/*Update Profile*/
public function iN_UpdateProfile($userID, $fulname, $bio, $newUsername, $birthDay, $profileCategory, $gender, $tipNot) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            // Get old username for cache invalidation
            $oldUser = DB::one("SELECT i_username FROM i_users WHERE iuid = ? LIMIT 1", [(int)$userID]);
            $oldUsername = $oldUser['i_username'] ?? null;

            $fields = [
                'i_username' => (string)$newUsername,
                'i_user_fullname' => (string)$fulname,
                'user_gender' => (string)$gender,
                'profile_category' => (string)$profileCategory,
                'u_bio' => (string)$bio,
            ];
            if ($birthDay) {
                $fields['birthday'] = $this->iN_CorrectDateFormat($birthDay);
            }
            $fields['thanks_for_tip'] = $tipNot ? (string)$tipNot : null;

            $sets = [];
            $params = [];
            foreach ($fields as $col => $val) { $sets[] = "$col = ?"; $params[] = $val; }
            $params[] = (int)$userID;
            $sql = "UPDATE i_users SET " . implode(', ', $sets) . " WHERE iuid = ?";
            DB::exec($sql, $params);

            // Invalidate user cache
            Cache::delete('user:id:' . (int)$userID);
            if ($oldUsername) {
                Cache::delete('user:username:' . $oldUsername);
            }
            if ($newUsername && $newUsername !== $oldUsername) {
                Cache::delete('user:username:' . $newUsername);
            }

            return true;
        } else {return false;}
	}
	/*INSERT UPLOADED COVER PHOTO*/
public function iN_INSERTUploadedCoverPhoto($uid, $filePath) {
		$uploadTime = time();
        $userIP = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($this->iN_CheckUserExist($uid) == 1) {
            DB::exec("INSERT INTO i_user_covers (iuid_fk, cover_path, cover_upload_time, ip) VALUES (?,?,?,?)", [(int)$uid, (string)$filePath, $uploadTime, (string)$userIP]);
            $ids = (int) DB::lastId();
            if ($ids) {
                DB::exec("UPDATE i_users SET user_cover = ? WHERE iuid = ?", [$ids, (int)$uid]);
                // Invalidate user cache after cover update
                Cache::delete('user:id:' . (int)$uid);
            }
            return $ids;
        } else {return false;}
	}
	/*GET UPLOADED FILE IDs*/
public function iN_GetUploadedCoverURL($uid, $imageID) {
		if ($imageID && $this->iN_CheckUserExist($uid) == 1) {
            $row = DB::one("SELECT cover_path FROM i_user_covers WHERE iuid_fk = ? AND cover_id = ? LIMIT 1", [(int)$uid, (int)$imageID]);
            return $row['cover_path'] ?? null;
        } else { return false; }
	}
	/*INSERT UPLOADED AVATAR PHOTO*/
public function iN_INSERTUploadedAvatarPhoto($uid, $filePath) {
		$uploadTime = time();
        $userIP = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($this->iN_CheckUserExist($uid) == 1) {
            DB::exec("INSERT INTO i_user_avatars (iuid_fk, avatar_path, avatar_upload_time, ip) VALUES (?,?,?,?)", [(int)$uid, (string)$filePath, $uploadTime, (string)$userIP]);
            $ids = (int) DB::lastId();
            if ($ids) {
                DB::exec("UPDATE i_users SET user_avatar = ? WHERE iuid = ?", [$ids, (int)$uid]);
                // Invalidate user cache after avatar update
                Cache::delete('user:id:' . (int)$uid);
            }
            return $ids;
        } else {return false;}
	}
	/*GET UPLOADED FILE IDs*/
public function iN_GetUploadedAvatarURL($uid, $imageID) {
		if ($imageID && $this->iN_CheckUserExist($uid) == 1) {
            $row = DB::one("SELECT avatar_path FROM i_user_avatars WHERE iuid_fk = ? AND avatar_id = ? LIMIT 1", [(int)$uid, (int)$imageID]);
            return $row['avatar_path'] ?? null;
        } else { return false; }
	}
	/*Check User Email Address*/
public function iN_CheckEmail($userID, $newEmail) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            $exists = (bool) DB::col("SELECT 1 FROM i_users WHERE i_user_email = ? AND iuid != ? LIMIT 1", [(string)$newEmail, (int)$userID]);
            return !$exists;
        } else { return false; }
	}
	/*Check User Password is Valid*/
public function iN_CheckUserPasswordAndUpdateIfIsValid($userID, $pass, $email) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $userDetails = $this->iN_GetUserDetails($userID);
            $storedHash = isset($userDetails['i_password']) ? (string)$userDetails['i_password'] : '';

            $isValidPassword = false;
            if ($storedHash !== '') {
                // Try modern password_verify first
                if (password_verify($pass, $storedHash)) {
                    $isValidPassword = true;
                } else {
                    // Fallback to legacy hashing methods
                    $legacyHash = sha1(md5($pass));
                    $legacySanitizedHash = sha1(md5($this->iN_Secure($pass)));
                    if (hash_equals($storedHash, $legacyHash) || hash_equals($storedHash, $legacySanitizedHash)) {
                        $isValidPassword = true;
                    }
                }
            }

            if ($isValidPassword) {
                DB::exec("UPDATE i_users SET i_user_email = ? WHERE iuid = ?", [(string)$email, (int)$userID]);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
	}
	/*Payments Subscriptions List*/
public function iN_PaymentsSubscriptionsList($userID, $paginationLimit, $page) {
		$paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $rows = DB::all(
                "SELECT DISTINCT S.*, U.*
                 FROM i_users U FORCE INDEX(ixForceUser)
                 INNER JOIN i_user_subscriptions S FORCE INDEX(ix_Subscribe)
                   ON S.subscribed_iuid_fk = U.iuid AND U.uStatus IN('1','3')
                 WHERE S.status IN('active','inactive') AND in_status IN('1','0') AND finished IN('0','1') AND S.subscribed_iuid_fk = ?
                 ORDER BY S.subscription_id DESC LIMIT $start_from, $paginationLimit",
                [(int)$userID]
            );
            return !empty($rows) ? $rows : null;
        }
	}
	/*Payments Subscriptions List*/
public function iN_PaymentsSubscriptionsListPage($userID, $paginationLimit, $page) {
		$paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $rows = DB::all(
                "SELECT DISTINCT S.*, U.*
                 FROM i_users U FORCE INDEX(ixForceUser)
                 INNER JOIN i_user_subscriptions S FORCE INDEX(ix_Subscribe)
                   ON S.iuid_fk = U.iuid AND U.uStatus IN('1','3')
                 WHERE S.status = 'active' AND S.iuid_fk = ?
                 ORDER BY S.subscription_id DESC LIMIT $start_from, $paginationLimit",
                [(int)$userID]
            );
            return !empty($rows) ? $rows : null;
        }
	}
	/*User Total Subscribers*/
public function iN_UserTotalSubscribers($userID) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_subscriptions WHERE subscribed_iuid_fk = ? AND status = 'active'", [(int)$userID]);
            return (int)$val;
        }
	}
	/*User Total Subscribtions*/
public function iN_UserTotalSubscribtions($userID) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_subscriptions WHERE iuid_fk = ? AND status = 'active'", [(int)$userID]);
            return (int)$val;
        }
	}
	/*Insert Payout Settings*/
public function iN_UpdatePayout($userID, $paypalEmail, $bankAccount, $defaultMethod) {
		if ($this->iN_CheckUserExist($userID) == 1) {
			DB::exec("UPDATE i_users SET payout_method = ?, paypal_email = ?, bank_account = ? WHERE iuid = ?",
				[(string)$defaultMethod, (string)$paypalEmail, (string)$bankAccount, (int)$userID]
			);
			return true;
		} else { return false; }
	}
	/*Insert Subscription Weekly*/
    public function iN_UpdateWeeklySubscriptionAmountAndStatus($userID, $SubWeekAmount, $weeklySubStatus) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserSetSubscriptionFeesBefore($userID, 'weekly') == '1') {
            DB::exec("UPDATE i_user_subscribe_plans SET amount = ?, plan_status = ? WHERE iuid_fk = ? AND plan_type = 'weekly'",
                [(string)$SubWeekAmount, (string)$weeklySubStatus, (int)$userID]
            );
            return true;
        } else {
            return $this->iN_InsertWeeklySubscriptionAmountAndStatus($userID, $SubWeekAmount, $weeklySubStatus);
        }
    }
	/*Insert Subscription Monthly*/
    public function iN_UpdateMonthlySubscriptionAmountAndStatus($userID, $SubMonthAmount, $monthlySubStatus) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserSetSubscriptionFeesBefore($userID, 'monthly') == '1') {
            DB::exec("UPDATE i_user_subscribe_plans SET amount = ?, plan_status = ? WHERE iuid_fk = ? AND plan_type = 'monthly'",
                [(string)$SubMonthAmount, (string)$monthlySubStatus, (int)$userID]
            );
            return true;
        } else {
            return $this->iN_InsertMonthlySubscriptionAmountAndStatus($userID, $SubMonthAmount, $monthlySubStatus);
        }
    }
	/*Insert Subscription Yearly*/
    public function iN_UpdateYearlySubscriptionAmountAndStatus($userID, $SubYearAmount, $yearlySubStatus) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserSetSubscriptionFeesBefore($userID, 'yearly') == '1') {
            DB::exec("UPDATE i_user_subscribe_plans SET amount = ?, plan_status = ? WHERE iuid_fk = ? AND plan_type = 'yearly'",
                [(string)$SubYearAmount, (string)$yearlySubStatus, (int)$userID]
            );
            return true;
        } else {
            return $this->iN_InsertYearlySubscriptionAmountAndStatus($userID, $SubYearAmount, $yearlySubStatus);
        }
    }
	/*Get User Weekly Subscription Plan*/
	public function iN_GetUserSubscriptionPlanDetails($userID, $planType) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            $row = DB::one("SELECT amount, plan_status, plan_type FROM i_user_subscribe_plans WHERE iuid_fk = ? AND plan_type = ? LIMIT 1", [(int)$userID, (string)$planType]);
            return $row ?: false;
        } else { return false; }
	}
	/*Payments Subscriptions List*/
public function iN_PayoutHistory($userID, $paginationLimit, $page) {
		$paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $rows = DB::all(
                "SELECT DISTINCT P.payout_id, P.iuid_fk, P.amount, P.method, P.payout_time, P.status, P.payment_type,
                        U.iuid, U.i_username, U.i_user_fullname
                 FROM i_users U FORCE INDEX(ixForceUser)
                 INNER JOIN i_user_payouts P FORCE INDEX(ix_PayoutUser)
                   ON P.iuid_fk = U.iuid AND U.uStatus IN('1','3')
                 WHERE P.iuid_fk = ?
                 ORDER BY P.payout_id DESC LIMIT $start_from, $paginationLimit",
                [(int)$userID]
            );
            return !empty($rows) ? $rows : null;
        }
	}
	/*Current Month Earn Calculate*/
public function iN_CalculateCurrentMonthEarning($userID) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            $row = DB::one("SELECT SUM(user_net_earning) AS calculate FROM i_user_subscriptions WHERE subscribed_iuid_fk = ? AND status IN('active','inactive') AND in_status IN('1','0') AND finished = '0'", [(int)$userID]);
            return $row ?: ['calculate' => 0];
        } else { return false; }
	}
	/*Insert Withdrawal*/
public function iN_InsertWithdrawal($userID, $withdrawalAmount, $defaultMethod, $payoutType) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            $time = time();
            DB::exec("INSERT INTO i_user_payouts (iuid_fk, amount, method, payment_type, payout_time, status) VALUES (?,?,?,?,?,'pending')",
                [(int)$userID, (string)$withdrawalAmount, (string)$defaultMethod, (string)$payoutType, $time]
            );
            DB::exec("UPDATE i_users SET wallet_money = wallet_money - ? WHERE iuid = ?", [(string)$withdrawalAmount, (int)$userID]);
            return true;
        } else { return false; }
	}
	/*Check User have Pending Withdrawal*/
public function iN_CheckUserHavePendingWithdrawal($userID) {
		if ($this->iN_CheckUserExist($userID) == 1) {
            return (bool) DB::col("SELECT 1 FROM i_user_payouts WHERE iuid_fk = ? AND payment_type = 'withdrawal' AND status = 'pending' LIMIT 1", [(int)$userID]);
        }
	}
	/*Payments history List*/
public function iN_PaymentsList($userID, $paginationLimit, $page) {
		$paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $rows = DB::all(
                "SELECT DISTINCT P.payment_id, P.payer_iuid_fk, P.payed_iuid_fk, P.payed_post_id_fk, P.payed_profile_id_fk, P.order_key,
                        P.payment_type, P.payment_option, P.payment_time, P.payment_status, P.amount, P.fee, P.admin_earning, P.user_earning,
                        U.iuid, U.i_username, U.i_user_fullname
                 FROM i_users U FORCE INDEX(ixForceUser)
                 INNER JOIN i_user_payments P FORCE INDEX(ixPayment)
                   ON P.payed_iuid_fk = U.iuid AND U.uStatus IN('1','3')
                 WHERE P.payment_status = 'ok' AND P.payed_iuid_fk = ?
                 ORDER BY P.payment_id DESC LIMIT $start_from, $paginationLimit",
                [(int)$userID]
            );
            return !empty($rows) ? $rows : null;
        }
	}
public function iN_CheckUserPurchasedThisPost($userID, $PurchasePostID) {
        return (bool) DB::col("SELECT 1 FROM i_user_payments WHERE payer_iuid_fk = ? AND payed_post_id_fk = ? LIMIT 1", [(int)$userID, (int)$PurchasePostID]);
	}
	/*Buy Post*/
public function iN_BuyPost($userID, $userPostOwnerID, $PurchasePostID, $amount, $adminEarning, $userEarning, $fee, $credit) {

		if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckUserExist($userPostOwnerID) == '1' && $this->iN_CheckPostIDExist($PurchasePostID) == '1' && $this->iN_CheckUserPurchasedThisPost($userID, $PurchasePostID) == '0') {
			$time = time();
			DB::exec("INSERT INTO i_user_payments (payer_iuid_fk, payed_iuid_fk, payed_post_id_fk, payment_type, payment_time, payment_status, amount, fee, admin_earning, user_earning)
                        VALUES (?,?,?, 'post', ?, 'ok', ?, ?, ?, ?)",
                        [(int)$userID, (int)$userPostOwnerID, (int)$PurchasePostID, $time, (string)$amount, (string)$fee, (string)$adminEarning, (string)$userEarning]
                );
			DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [(int)$credit, (int)$userID]);
			DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [(string)$userEarning, (int)$userPostOwnerID]);
			return true;
		} else {
			return false;
		}
	}
	/*Premium Plan List*/
public function iN_PremiumPlans() {
		$rows = DB::all("SELECT * FROM i_premium_plans WHERE plan_status = '1'");
		if (!empty($rows)) { $data = $rows; }
		if (!empty($data)) {
			return $data;
		}
	}
	/*Check Premium Plan Exist*/
public function CheckPlanExist($planID) {
		return (bool) DB::col("SELECT 1 FROM i_premium_plans WHERE plan_id = ? LIMIT 1", [(int)$planID]);
}
	/*Check Premium Plan Exist*/
public function CheckLivePlanExist($planID) {
		return (bool) DB::col("SELECT 1 FROM i_live_gift_point WHERE gift_id = ? LIMIT 1", [(int)$planID]);
}
    /*Check Frame Plan Exist*/
public function CheckFramePlanExist($planID) {
		return (bool) DB::col("SELECT 1 FROM i_frames WHERE f_id = ? LIMIT 1", [(int)$planID]);
}
	/*Check Premium Plan Exist*/
public function GetPlanDetails($planID) {
		if ($this->CheckPlanExist($planID) == '1') {
			return DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ? LIMIT 1", [(int)$planID]);
		} else {return false;}
}
	/*Text REplacement*/
	public function iN_TextReaplacement($string, $values = []) {
		preg_match_all('/\{(\w+)\}/', $string, $matches);
		return str_replace($matches[0], $values, $string);
	}
	/*Get Latest Payment Details*/
public function iN_LatestPaymentPost($userID) {
		if ($this->iN_CheckUserExist($userID) == '1') {
			return DB::one("SELECT * FROM i_user_payments WHERE payer_iuid_fk = ? AND payment_type = 'post' AND payment_status = 'ok' ORDER BY payment_id DESC LIMIT 1", [(int)$userID]);
		} else { return false; }
}
	/*Get Latest Payment Details*/
    public function iN_LatestPaymentPoint($userID) {
        if ($this->iN_CheckUserExist($userID) == '1') {
            return DB::one(
                "SELECT * FROM i_user_payments WHERE payer_iuid_fk = ? AND payment_type IN('point','product') AND payment_status = 'pending' AND payment_time >= UNIX_TIMESTAMP(NOW() - INTERVAL 1 MINUTE) ORDER BY payment_id DESC LIMIT 1",
                [(int)$userID]
            );
        } else { return false; }
    }
	/*Update Payment Success Status*/
    public function iN_UpdatePaymentSuccessStatus($userID, $paymentID){
        if ($this->iN_CheckUserExist($userID) == '1') {
            DB::exec("UPDATE i_user_payments SET payment_status = 'ok' WHERE payment_id = ?", [(int)$paymentID]);
            return true;
        }
    }
public function iN_ChatUserList($userID, $limit) {
    $limit = (int)$limit;
    if ($this->iN_CheckUserExist($userID) == '1') {
        $rows = DB::all(
            "SELECT DISTINCT C.chat_id, C.user_one, C.user_two, C.last_message_time,
                    U.iuid, U.i_username, U.i_user_fullname, U.user_avatar, U.online_offline_status,
                    U.last_login_time, U.user_gender, U.user_verified_status
             FROM i_chat_users C FORCE INDEX (ixUserChat)
             INNER JOIN i_users U FORCE INDEX (ixForceUser) ON C.user_one = U.iuid
             WHERE U.uStatus IN('1','3') AND (C.user_two = ? OR C.user_one = ?)
             ORDER BY C.last_message_time DESC
             LIMIT $limit",
            [(int)$userID, (int)$userID]
        );
        return !empty($rows) ? $rows : null;
    }
}
	/*Check Chat ID Exist*/
    public function iN_CheckChatIDExist($chatID) {
        return (bool) DB::col("SELECT 1 FROM i_chat_users WHERE chat_id = ? LIMIT 1", [(int)$chatID]);
    }
	/*Check Chat Owners Exist*/
    public function iN_CheckChatUserOwnersID($userID, $chatID) {
        if ($this->iN_CheckChatIDExist($chatID) == '1'){
            return (bool) DB::col("SELECT 1 FROM i_chat_users WHERE chat_id = ? AND (user_one = ? OR user_two = ?) LIMIT 1", [(int)$chatID, (int)$userID, (int)$userID]);
        }
    }
	/*Check Chat ID Exist*/
    public function iN_GetChatUserIDs($chatID) {
        return DB::one("SELECT * FROM i_chat_users WHERE chat_id = ? LIMIT 1", [(int)$chatID]);
    }
	/*Chat Latest Message*/
    public function iN_GetLatestMessage($chatID) {
        if ($this->iN_CheckChatIDExist($chatID) == '1') {
            return DB::one("SELECT * FROM i_chat_conversations WHERE chat_id_fk = ? ORDER BY con_id DESC LIMIT 1", [(int)$chatID]);
        }
    }
	/*Chat Messages*/
	public function iN_GetChatMessages($userID, $chatID, $lastMessageID, $messageLimit) {
		$messageLimit = (int)$messageLimit;
		if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckChatIDExist($chatID) == '1') {
			$params = [];
			$where = '';
			if (!empty($lastMessageID)) { $where = 'M.con_id < ? AND '; $params[] = (int)$lastMessageID; }
			$params[] = (int)$chatID;
			$sql = "SELECT * FROM (
					SELECT DISTINCT M.con_id, M.chat_id_fk, M.user_one, M.user_two, M.private_price, M.private_status, M.gifMoney,
							M.message, M.seen_status, M.file, M.sticker_url, M.gifurl, M.time,
							C.chat_id, U.iuid, U.uStatus, U.i_username, U.i_user_fullname, U.user_gender
					FROM i_chat_users C FORCE INDEX(ixUserChat)
					INNER JOIN i_chat_conversations M FORCE INDEX(ixChat) ON C.chat_id = M.chat_id_fk
					INNER JOIN i_users U FORCE INDEX(ixForceUser) ON M.user_one = U.iuid AND U.uStatus IN('1','3')
					WHERE $where M.chat_id_fk = ?
					ORDER BY M.con_id DESC
					LIMIT $messageLimit
				) t ORDER BY con_id ASC";
			return DB::all($sql, $params);
		}
	}
	/*Get Latest Message*/
	public function iN_GetUserNewMessage($uid, $chatID, $user, $lastMessageID) {
		$sql = "SELECT DISTINCT M.con_id, M.chat_id_fk, M.user_one, M.user_two, M.gifMoney, M.private_price, M.private_status,
					M.message, M.file, M.seen_status, M.sticker_url, M.gifurl, M.time,
					C.chat_id, U.iuid, U.uStatus, U.i_username, U.i_user_fullname, U.user_gender
			FROM i_chat_users C FORCE INDEX(ixUserChat)
			INNER JOIN i_chat_conversations M FORCE INDEX(ixChat) ON C.chat_id = M.chat_id_fk
			INNER JOIN i_users U FORCE INDEX(ixForceUser) ON M.user_one = U.iuid AND U.uStatus IN('1','3')
			WHERE M.con_id > ? AND M.user_one = ? AND M.chat_id_fk = ?
			ORDER BY M.con_id DESC LIMIT 1";
		return DB::one($sql, [(int)$lastMessageID, (int)$user, (int)$chatID]);
	}
	/*Insert New Message*/
	public function iN_InsertNewMessage($userID, $chatID, $message, $stickerURL, $gif, $fileIDs, $mMoney) {
		if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckChatIDExist($chatID) == '1') {
			$time = time();
			$cData = $this->iN_GetChatUserIDs($chatID);
			$chatUserOne = $cData['user_one'];
			$chatUserTwo = $cData['user_two'];
			$toID = ($chatUserOne == $userID) ? $chatUserTwo : $chatUserOne;
			DB::exec("INSERT INTO i_chat_conversations (chat_id_fk, user_one, user_two, message, sticker_url, gifurl, file, time, private_status, private_price) VALUES (?,?,?,?,?,?,?,?,'closed',?)",
				[(int)$chatID, (int)$userID, (int)$toID, (string)$message, (string)$stickerURL, (string)$gif, (string)$fileIDs, $time, (string)$mMoney]
			);
			DB::exec("UPDATE i_chat_users SET last_message_time = ? WHERE chat_id = ?", [$time, (int)$chatID]);
			$row = DB::one("SELECT * FROM (
						SELECT DISTINCT M.con_id, M.chat_id_fk, M.user_one, M.user_two, M.gifMoney, M.private_price, M.private_status,
								M.message, M.file, M.sticker_url, M.gifurl, M.seen_status, M.time,
								C.chat_id, U.iuid, U.uStatus, U.i_username, U.i_user_fullname, U.user_gender
						FROM i_chat_users C FORCE INDEX(ixUserChat)
						INNER JOIN i_chat_conversations M FORCE INDEX(ixChat) ON C.chat_id = M.chat_id_fk
						INNER JOIN i_users U FORCE INDEX(ixForceUser) ON M.user_one = U.iuid AND U.uStatus IN('1','3')
						WHERE M.chat_id_fk = ? AND M.user_one = ? AND M.user_two = ?
						ORDER BY M.con_id DESC LIMIT 1
					) t ORDER BY con_id ASC", [(int)$chatID, (int)$userID, (int)$toID]);
			DB::exec("UPDATE i_users SET message_notification_read_status = '1' WHERE iuid = ?", [(int)$toID]);
			return $row ?: null;
		}
		return false;
	}
	/*INSERT UPLOADED FILES FROM UPLOADS TABLE*/
	public function iN_INSERTUploadedMessageFiles($uid, $conversationID, $filePath, $fileXPath, $ext) {
		$uploadTime = time();
		$userIP = $_SERVER['REMOTE_ADDR'] ?? '';
		DB::exec("INSERT INTO i_user_conversation_uploads (iuid_fk, con_id_fk, uploaded_file_path, uploaded_x_file_path, uploaded_file_ext, upload_time, ip) VALUES (?,?,?,?,?,?,?)",
			[(int)$uid, (int)$conversationID, (string)$filePath, (string)$fileXPath, (string)$ext, $uploadTime, (string)$userIP]
		);
		return (int) DB::lastId();
	}
	/*GET UPLOADED FILE IDs*/
	public function iN_GetUploadedMessageFilesIDs($uid, $imageName) {
		if ($imageName) { return DB::one("SELECT upload_id, uploaded_file_path FROM i_user_conversation_uploads WHERE iuid_fk = ? ORDER BY upload_id DESC LIMIT 1", [(int)$uid]); }
		return false;
	}
	/*GET UPLOADED FILE DATA*/
	public function iN_GetUploadedMessageFileDetails($imageID) {
		if (!$imageID || !is_numeric($imageID)) { return false; }
		return DB::one("SELECT * FROM i_user_conversation_uploads WHERE upload_id = ? LIMIT 1", [(int)$imageID]);
	}
	/*Update User Typing*/
	public function iN_UpdateTypingStatus($userID, $conversationID, $time) {
		if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckChatIDExist($conversationID) == '1') {
			DB::exec("UPDATE i_chat_users SET typing_user_one = ? WHERE chat_id = ?", [(int)$time, (int)$conversationID]);
		}
	}
	/*Check Conversation Started Before*/
	public function iN_CheckConversationStartedBeforeBetweenUsers($userID, $iuID) {
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($iuID) == 1) {
			return (bool) DB::col("SELECT 1 FROM i_chat_users WHERE (user_one = ? AND user_two = ?) OR (user_two = ? AND user_one = ?) LIMIT 1", [(int)$userID, (int)$iuID, (int)$userID, (int)$iuID]);
		}
	}
	/*Check User Typing Status*/
	public function iN_GetTypingStatus($userID, $conversationID) {
		if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckChatIDExist($conversationID) == '1') {
			$val = DB::col("SELECT typing_user_one FROM i_chat_users WHERE chat_id = ?", [(int)$conversationID]);
			return $val !== false ? $val : false;
		}
	}
	/*Update Message Seen*/
    public function iN_UpdateMessageSeenStatus($cID, $toUserID, $userID) {
        if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckChatIDExist($cID) == '1') {
            DB::exec("UPDATE i_chat_conversations SET seen_status = '1' WHERE chat_id_fk = ? AND user_one = ?", [(int)$cID, (int)$toUserID]);
        }
    }
	/*Update Message Seen*/
    public function iN_CheckLastMessageSeenOrNot($cID, $toUserID, $userID) {
        if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckChatIDExist($cID) == '1') {
            $notReaded = (int) DB::col(
                "SELECT COUNT(*) FROM i_chat_conversations WHERE chat_id_fk = ? AND user_one = ? AND user_two = ? AND seen_status = '0'",
                [(int)$cID, (int)$userID, (int)$toUserID]
            );
            return $notReaded > 0 ? '0' : '1';
        }
    }
	/*Get Total Unreaded notifications */
public function iN_GetNewMessageNotificationSum($userID) {
		if ($this->iN_CheckUserExist($userID) == 1) {
			$val = DB::col("SELECT COUNT(*) FROM i_chat_conversations WHERE user_two = ? AND seen_status = '0'", [(int)$userID]);
			return (int)$val;
		}
		return false;
}
	/*Update Message Notification Status 1 to 0*/
public function iN_UpdateMessageNotificationStatus($userID) {
		if ($this->iN_CheckUserExist($userID) == 1) {
			DB::exec("UPDATE i_users SET message_notification_read_status = '0' WHERE iuid = ?", [(int)$userID]);
		}
}
	/*Popular User From Last Week*/
public function iN_PopularUsersFromLastWeek() {
        $sql = "SELECT DISTINCT P.post_owner_id, U.iuid, U.i_username, U.i_user_fullname, U.user_verified_status, U.user_gender,
                       U.certification_status, U.validation_status, U.condition_status, U.fees_status, COUNT(P.post_owner_id) AS cnt
                FROM i_posts P FORCE INDEX(ixForcePostOwner)
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3')
                WHERE WEEK(FROM_UNIXTIME(P.post_created_time)) = WEEK(NOW()) - 1
                  AND U.certification_status = '2' AND U.validation_status = '2' AND U.condition_status = '2' AND U.fees_status = '2'
                GROUP BY P.post_owner_id ORDER BY cnt DESC LIMIT 5";
        return DB::all($sql);
}
	/*Get All Posts For Explore Page*/
public function iN_AllUserForExplore($uid, $lastPostID, $showingPost) {
		$showingPosts = (int)$showingPost;
		$params = [(int)$uid]; // For user_likes JOIN
		$where = '';
		if (!empty($lastPostID)) { $where = ' AND P.post_id < ?'; $params[] = (int)$lastPostID; }

		// OPTIMIZED: Added LEFT JOINs for likes count, comments count, and user like status
		$sql = "SELECT P.*, U.*,
			IFNULL(likes.total_likes, 0) AS total_likes,
			IFNULL(comments.total_comments, 0) AS total_comments,
			IF(user_likes.like_id IS NOT NULL, 1, 0) AS liked_by_user
			FROM i_posts P FORCE INDEX (ixForcePostOwner)
			INNER JOIN i_users U FORCE INDEX (ixForceUser) ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3')
			LEFT JOIN (
				SELECT post_id_fk, COUNT(*) AS total_likes
				FROM i_post_likes
				GROUP BY post_id_fk
			) likes ON P.post_id = likes.post_id_fk
			LEFT JOIN (
				SELECT comment_post_id_fk, COUNT(*) AS total_comments
				FROM i_post_comments
				GROUP BY comment_post_id_fk
			) comments ON P.post_id = comments.comment_post_id_fk
			LEFT JOIN i_post_likes user_likes ON P.post_id = user_likes.post_id_fk AND user_likes.iuid_fk = ?
			WHERE P.post_status IN('0','1') AND P.post_type NOT IN('reels') $where
			ORDER BY P.post_id DESC
			LIMIT $showingPosts";
		$rows = DB::all($sql, $params);
		return !empty($rows) ? $rows : null;
}
	/*Get All Posts For Premium Page*/
public function iN_AllUserForPremium($uid, $lastPostID, $showingPost) {
		$showingPosts = (int)$showingPost;
		$params = [(int)$uid]; // For user_likes JOIN
		$where = '';
		if (!empty($lastPostID)) { $where = ' AND P.post_id < ?'; $params[] = (int)$lastPostID; }

		// OPTIMIZED: Added LEFT JOINs for likes count, comments count, and user like status
		$sql = "SELECT DISTINCT P.*, U.*,
			IFNULL(likes.total_likes, 0) AS total_likes,
			IFNULL(comments.total_comments, 0) AS total_comments,
			IF(user_likes.like_id IS NOT NULL, 1, 0) AS liked_by_user
			FROM i_posts P FORCE INDEX (ixForcePostOwner)
			INNER JOIN i_users U FORCE INDEX (ixForceUser)
			ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3')
			LEFT JOIN (
				SELECT post_id_fk, COUNT(*) AS total_likes
				FROM i_post_likes
				GROUP BY post_id_fk
			) likes ON P.post_id = likes.post_id_fk
			LEFT JOIN (
				SELECT comment_post_id_fk, COUNT(*) AS total_comments
				FROM i_post_comments
				GROUP BY comment_post_id_fk
			) comments ON P.post_id = comments.comment_post_id_fk
			LEFT JOIN i_post_likes user_likes ON P.post_id = user_likes.post_id_fk AND user_likes.iuid_fk = ?
			WHERE P.post_status IN('1') AND P.who_can_see IN('4') AND P.post_type NOT IN('reels') $where
			ORDER BY P.post_id DESC
			LIMIT $showingPosts";
		$rows = DB::all($sql, $params);
		return !empty($rows) ? $rows : null;
}
	/*Creators List For Menu*/
public function iN_CreatorTypes() {
		$rows = DB::all("SELECT * FROM i_creators WHERE creator_status = '1'");
		return !empty($rows) ? $rows : null;
}
	/*Check Creator Type Exist*/
public function iN_CheckCreatorTypeExist($creatorType) {
		return (bool) DB::col("SELECT 1 FROM i_creators WHERE creator_value = ? AND creator_status = '1' LIMIT 1", [(string)$creatorType]);
}
	/*Popular User From Last Week*/
    public function iN_PopularUsersFromLastWeekInExplorePage() {
        $sql = "SELECT DISTINCT
                P.post_owner_id, U.iuid, U.user_frame, U.i_username, U.i_user_fullname, U.user_verified_status,
                U.user_gender, U.certification_status, U.validation_status, U.condition_status, U.fees_status,
                COUNT(P.post_owner_id) AS cnt
            FROM i_posts P FORCE INDEX(ixForcePostOwner)
            INNER JOIN i_users U FORCE INDEX(ixForceUser)
              ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3')
            WHERE U.user_verified_status = '1'
              AND WEEK(FROM_UNIXTIME(P.post_created_time)) = WEEK(NOW()) - 1
              AND U.certification_status = '2' AND U.validation_status = '2'
              AND U.condition_status = '2' AND U.fees_status = '2'
            GROUP BY P.post_owner_id
            ORDER BY cnt DESC
            LIMIT 30";
        $rows = DB::all($sql);
        return !empty($rows) ? $rows : null;
    }
	public function iN_ExploreUserLatestFivePost($userID) {
		$sql = "SELECT DISTINCT P.*, U.*
			FROM i_posts P FORCE INDEX (ixForcePostOwner)
			INNER JOIN i_users U FORCE INDEX (ixForceUser) ON P.post_owner_id = U.iuid
			WHERE P.post_owner_id = ? AND U.uStatus IN('1','3') AND P.post_file <> '' AND P.post_status = '1' AND P.post_file IS NOT NULL
			ORDER BY P.post_id DESC LIMIT 5";
		$rows = DB::all($sql, [(int)$userID]);
		return !empty($rows) ? $rows : null;
	}
	/*Display the developer list requested by url*/
	public function iN_GetCreatorFromUrl($requested, $lastUID, $userLimit) {
		$userLimit = (int)$userLimit;
		$params = [(string)$requested];
		$where = '';
		if (!empty($lastUID)) { $where = ' AND iuid < ?'; $params[] = (int)$lastUID; }
		$sql = "SELECT * FROM i_users WHERE profile_category = ? AND certification_status = '2' AND validation_status = '2' AND condition_status = '2' AND fees_status = '2' AND payout_status = '2' AND uStatus IN('1','3') $where ORDER BY iuid DESC LIMIT $userLimit";
		$rows = DB::all($sql, $params);
		return !empty($rows) ? $rows : null;
	}
	/*Calculate Subscription Earnings*/
	public function iN_CalculateSubEarnings($userID) {
		if ($this->iN_CheckUserExist($userID) == '1') {
			$row = DB::one("SELECT SUM(user_net_earning) AS subEarn FROM i_user_subscriptions WHERE subscribed_iuid_fk = ? AND status IN('active','inactive') AND in_status IN('1','0') AND finished = '0' AND MONTH(created)= MONTH(CURDATE())", [(int)$userID]);
			return isset($row['subEarn']) ? $row['subEarn'] : '0.00';
		}
	}
	/*Calculate Premium Earnings*/
	public function iN_CalculatePremiumEarnings($userID) {
		if ($this->iN_CheckUserExist($userID) == '1') {
			$row = DB::one("SELECT SUM(user_earning) AS premiumEarn FROM i_user_payments WHERE payed_iuid_fk = ? AND MONTH(FROM_UNIXTIME(payment_time))= MONTH(CURDATE())", [(int)$userID]);
			return isset($row['premiumEarn']) ? $row['premiumEarn'] : '0.00';
		}
	}
	/*SAVED POSTS*/
public function iN_SavedPosts($userID, $lastPostID, $showingPost) {
		$showingPosts = (int)$showingPost;
		$params = [(int)$userID, (int)$userID]; // For user_likes JOIN and WHERE clause
		$where = '';
		if (!empty($lastPostID)) { $where = ' AND S.saved_post_id < ?'; $params[] = (int)$lastPostID; }

		// OPTIMIZED: Added LEFT JOINs for likes count, comments count, and user like status
		$sql = "SELECT DISTINCT P.post_id,P.shared_post_id,P.post_pined,P.comment_status,P.post_owner_id,P.post_text,P.post_file,P.post_created_time,P.who_can_see,P.post_want_status,P.url_slug,P.post_wanted_credit,P.post_status,
		               S.save_id, S.iuid_fk, S.saved_post_id,
		               U.iuid,U.i_username,U.i_user_fullname,U.payout_method,U.thanks_for_tip,U.user_avatar,U.user_gender,U.last_login_time,U.user_verified_status,
		               IFNULL(likes.total_likes, 0) AS total_likes,
		               IFNULL(comments.total_comments, 0) AS total_comments,
		               IF(user_likes.like_id IS NOT NULL, 1, 0) AS liked_by_user
		        FROM i_saved_posts S FORCE INDEX(ixSaved)
		        INNER JOIN i_posts P FORCE INDEX (ixForcePostOwner) ON S.saved_post_id = P.post_id
		        INNER JOIN i_users U FORCE INDEX (ixForceUser) ON S.iuid_fk = U.iuid AND U.uStatus IN('1','3')
		        LEFT JOIN (
		            SELECT post_id_fk, COUNT(*) AS total_likes
		            FROM i_post_likes
		            GROUP BY post_id_fk
		        ) likes ON P.post_id = likes.post_id_fk
		        LEFT JOIN (
		            SELECT comment_post_id_fk, COUNT(*) AS total_comments
		            FROM i_post_comments
		            GROUP BY comment_post_id_fk
		        ) comments ON P.post_id = comments.comment_post_id_fk
		        LEFT JOIN i_post_likes user_likes ON P.post_id = user_likes.post_id_fk AND user_likes.iuid_fk = ?
		        WHERE S.iuid_fk = ? $where
		        ORDER BY S.saved_post_id DESC
		        LIMIT $showingPosts";
		$rows = DB::all($sql, $params);
		return !empty($rows) ? $rows : null;
}
	/*Update User Language*/
	public function iN_UpdateLanguage($userID, $langID) {
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckLangIDExist($langID)) {
			$langKey = $this->iN_CheckLangIDExist($langID);
			DB::exec("UPDATE i_users SET lang = ? WHERE iuid = ?", [(string)$langKey, (int)$userID]);
			return true;
		} else { return false; }
	}
	/*Search User*/
public function iN_GetSearchResult($key, $showingNumberOfPost, $Users) {
        $limit = (int)$showingNumberOfPost;
        $like = '%' . $key . '%';
        if ($Users == 'yes') {
            $sql = "SELECT * FROM i_users WHERE (i_username LIKE ? OR i_user_fullname LIKE ? OR i_username LIKE ? OR i_user_fullname LIKE ?) AND uStatus IN('1','3') AND certification_status = '2' AND validation_status = '2' AND condition_status = '2' AND fees_status = '2' AND payout_status = '2' ORDER BY iuid LIMIT $limit";
        } else {
            $sql = "SELECT * FROM i_users WHERE (i_username LIKE ? OR i_user_fullname LIKE ? OR i_username LIKE ? OR i_user_fullname LIKE ?) AND uStatus IN('1','3') ORDER BY iuid LIMIT $limit";
        }
        return DB::all($sql, [$like, $like, $like, $like]);
    }
	/*Create a Conversation and Insert First Message Between Users*/
public function iN_CreateConverationAndInsertFirstMessage($userID, $user, $firstMessage) {
        if ($this->iN_CheckUserExist($user) == 1 && $this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckConversationStartedBeforeBetweenUsers($userID, $user) == 0) {
            DB::exec("INSERT INTO i_chat_users (user_one, user_two) VALUES (?,?)", [(int)$userID, (int)$user]);
            $row = DB::one("SELECT chat_id FROM i_chat_users WHERE user_one = ? AND user_two = ? ORDER BY chat_id DESC LIMIT 1", [(int)$userID, (int)$user]);
            if ($row) {
                $chatID = (int)$row['chat_id'];
                $time = time();
                DB::exec("INSERT INTO i_chat_conversations (chat_id_fk, user_one, user_two, message, time) VALUES (?,?,?,?,?)", [$chatID, (int)$userID, (int)$user, (string)$firstMessage, $time]);
                return $chatID;
            }
            return false;
        } else { return false; }
    }
	/*Get Conversation ID Between Two Users*/
public function iN_GetConverationID($userIDone, $userIDTwo) {
        if ($this->iN_CheckUserExist($userIDone) == 1 && $this->iN_CheckUserExist($userIDTwo) == 1) {
            $row = DB::one("SELECT chat_id FROM i_chat_users WHERE (user_one = ? AND user_two = ?) OR (user_one = ? AND user_two = ?) ORDER BY chat_id DESC LIMIT 1", [(int)$userIDone, (int)$userIDTwo, (int)$userIDTwo, (int)$userIDone]);
            return $row ? (int)$row['chat_id'] : false;
        }
    }
	/*Update Theme*/
	public function iN_UpdateUserTheme($userID, $uTheme) {
		if ($this->iN_CheckUserExist($userID) == 1) {
			DB::exec("UPDATE i_users SET light_dark = ? WHERE iuid = ?", [(string)$uTheme, (int)$userID]);
			return true;
		}
	}
	/*Check Message ID Exist*/
public function iN_CheckMesageIDExist($userID, $messageID, $conversationID) {
        return (bool) DB::col("SELECT 1 FROM i_chat_conversations WHERE chat_id_fk = ? AND con_id = ? AND user_one = ? LIMIT 1", [(int)$conversationID, (int)$messageID, (int)$userID]);
    }
	/*Delete Message*/
public function iN_DeleteMessageFromData($userID, $messageID, $conversationID) {
        if ($this->iN_CheckMesageIDExist($userID, $messageID, $conversationID) == 1) {
            DB::exec("DELETE FROM i_chat_conversations WHERE chat_id_fk = ? AND con_id = ?", [(int)$conversationID, (int)$messageID]);
            return true;
        }
        return false;
    }
	/*Check Conversation Exist*/
public function iN_CheckConversationIDExist($conversationID, $userID){
    DB::exec("DELETE FROM i_chat_users WHERE chat_id = ? AND (user_one = ? OR user_two = ?)", [(int)$conversationID,(int)$userID,(int)$userID]);
    return true;
}
	/*Delete This Conversation*/
public function iN_DeleteConversationFromData($userID, $conversationID) {
        if ($this->iN_CheckConversationIDExist($conversationID, $userID) == '1') {
            try {
                DB::begin();
                DB::exec("DELETE FROM i_chat_conversations WHERE chat_id_fk = ?", [(int)$conversationID]);
                DB::exec("DELETE FROM i_chat_users WHERE chat_id = ?", [(int)$conversationID]);
                DB::commit();
                return true;
            } catch (Throwable $e) {
                DB::rollBack();
                return false;
            }
        }
}
	/*Search Following Users*/
public function iN_SearchChatUsers($userID, $searchKey) {
        if ($this->iN_CheckUserExist($userID) == 1 && !empty($searchKey)) {
            $like = $searchKey . '%';
            $sql = "SELECT * FROM i_users WHERE (i_username LIKE ? OR i_user_fullname LIKE ? OR i_user_fullname LIKE ? OR i_username LIKE ?) AND uStatus IN('1','3') AND (SELECT COUNT(*) FROM i_friends WHERE fr_one = ? AND fr_two <> ? AND fr_status <> 'me') = 1 ORDER BY iuid LIMIT 10";
            $rows = DB::all($sql, [$searchKey.'%', $like, $searchKey, $searchKey, (int)$userID, (int)$userID]);
            foreach ($rows as $row) { $data[] = $this->iN_GetUserDetails($row['iuid']); }
            if (!empty($data)) { return $data; }
        }
}
	/*Hide Noticiation*/
public function iN_HideNotification($userID, $hideID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckNotificationIDExist($hideID) == 1) {
            DB::exec("UPDATE i_user_notifications SET not_show_hide = '1' WHERE not_id = ? AND not_own_iuid = ?", [(int)$hideID,(int)$userID]);
            return true;
        }
}
	/*User Total Blocked User*/
public function iN_UserTotalBlocks($userID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_blocks WHERE blocker_iuid = ?", [(int)$userID]);
            return (int)$val;
        }
}
	/*Payments Subscriptions List*/
public function iN_UserBlockedListPage($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $sql = "SELECT DISTINCT B.block_id,B.blocker_iuid,B.blocked_iuid,B.block_type,B.blocked_time,U.iuid,U.i_username,U.i_user_fullname
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_user_blocks B FORCE INDEX(ixBlocked) ON B.blocked_iuid = U.iuid AND U.uStatus IN('1','3')
                    WHERE B.blocker_iuid = ? ORDER BY B.block_id DESC LIMIT $start_from, $paginationLimit";
            $rows = DB::all($sql, [(int)$userID]);
            return !empty($rows) ? $rows : null;
        }
}
	/*UnBlock User*/
public function iN_UnBlockUser($userID, $unBlockID, $unBlockUserID) {
        if ($this->iN_CheckUserBlocked($userID, $unBlockUserID) == 1 && $this->iN_CheckUserExist($userID) == 1) {
            DB::exec("DELETE FROM i_user_blocks WHERE block_id = ? AND blocker_iuid = ? AND blocked_iuid = ?", [(int)$unBlockID,(int)$userID,(int)$unBlockUserID]);
            return true;
        } else { return false; }
}
	/*Update Password*/
public function iN_UpdatePassword($userID, $newPassword) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            try {
                DB::begin();
                DB::exec("UPDATE i_users SET i_password = ? WHERE iuid = ? AND uStatus IN('1','3')", [(string)$newPassword,(int)$userID]);
                DB::exec("DELETE FROM i_sessions WHERE session_uid = ?", [(int)$userID]);
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        } else { return false; }
}
	/*Update Profile Preferences (Email notification)*/
public function iN_UpdateEmailNotificationStatus($userID, $status) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("UPDATE i_users SET email_notification_status = ? WHERE iuid = ?", [(string)$status,(int)$userID]);
            return true;
        } else { return false; }
}
	/*Update Profile Preferences (Message Send Status)*/
public function iN_UpdateMessageSendStatus($userID, $status) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("UPDATE i_users SET message_status = ? WHERE iuid = ?", [(string)$status,(int)$userID]);
            return true;
        } else { return false; }
}
	/*Update Profile Preferences (Message Send Status)*/
public function iN_UpdateShowHidePostsStatus($userID, $status) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("UPDATE i_users SET show_hide_posts = ? WHERE iuid = ?", [(string)$status,(int)$userID]);
            return true;
        } else { return false; }
}
	/*Total Subscription Earnings*/
public function iN_TotalSubscriptionEarnings($userID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT SUM(admin_earning) FROM i_user_subscriptions WHERE status IN('active','inactive') AND in_status IN('1','0') AND finished = '0'");
            return $val ? (string)$val : '0.00';
        }
}
	/*Total Admin Premium Earnings*/
public function iN_TotalPremiumEarnings($userID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT SUM(admin_earning) FROM i_user_payments WHERE payment_status = 'ok'");
            return $val ? (string)$val : '0.00';
        }
}
	/*Total User*/
public function iN_TotalUsers() {
        $val = DB::col("SELECT COUNT(*) FROM i_users WHERE uStatus IN('1','2','3')");
        return (int)$val;
}
	/*Total Posts*/
    public function iN_TotalUserPosts() {
        $val = DB::col("SELECT COUNT(*) FROM i_posts");
        return (int)$val;
    }
	/*Total Sticker*/
    public function iN_TotalSticker() {
        $val = DB::col("SELECT COUNT(*) FROM i_stickers");
        return (int)$val;
    }
	/*Total Withdrawal Payments*/
    public function iN_TotalUsersWithdrawals() {
        $val = DB::col("SELECT COUNT(*) FROM i_user_payouts WHERE payment_type = 'withdrawal'");
        return (int)$val;
    }
	/*Total Subscription Payments*/
    public function iN_TotalUsersSubscriptions() {
        $val = DB::col("SELECT COUNT(*) FROM i_user_payouts WHERE payment_type = 'subscription'");
        return (int)$val;
    }
	/*Total Premium Earnings Weekly*/
    public function iN_WeeklyTotalPremiumEarning() {
        $val = DB::col("SELECT SUM(admin_earning) FROM i_user_payments WHERE payment_status = 'ok' AND WEEK(FROM_UNIXTIME(payment_time)) = WEEK(NOW())");
        return $val ? (string)$val : '0.00';
    }
	/*Total Premium Earnings Current Day*/
    public function iN_CurrentDayTotalPremiumEarning() {
        $val = DB::col("SELECT SUM(admin_earning) FROM i_user_payments WHERE payment_status = 'ok' AND DAY(FROM_UNIXTIME(payment_time)) = DAY(CURDATE())");
        return $val ? (string)$val : '0.00';
    }
	/*Total Premium Earnings Current Month*/
public function iN_CurrentMonthTotalPremiumEarning() {
        $val = DB::col("SELECT SUM(admin_earning) FROM i_user_payments WHERE payment_status = 'ok' AND MONTH(FROM_UNIXTIME(payment_time)) = MONTH(CURDATE())");
        return $val ? (string)$val : '0.00';
}
	/*Total Premium Earnings Weekly*/
public function iN_WeeklyTotalSubscriptionEarning() {
        $val = DB::col("SELECT SUM(admin_earning) FROM  i_user_subscriptions WHERE status IN('active','inactive') AND in_status IN('1','0') AND finished = '0' AND WEEK(created) = WEEK(NOW())");
        return $val ? (string)$val : '0.00';
}
	/*Total Premium Earnings Current Day*/
public function iN_CurrentDayTotalSubscriptionEarning() {
        $val = DB::col("SELECT SUM(admin_earning) FROM  i_user_subscriptions WHERE status IN('active','inactive') AND in_status IN('1','0') AND finished = '0' AND DAY(created) = DAY(CURDATE())");
        return $val ? (string)$val : '0.00';
}
	/*Total Premium Earnings Current Month*/
public function iN_CurrentMonthTotalSubscriptionEarning() {
        $val = DB::col("SELECT SUM(admin_earning) FROM  i_user_subscriptions WHERE status IN('active','inactive') AND in_status IN('1','0') AND finished = '0' AND MONTH(created) = MONTH(CURDATE())");
        return $val ? (string)$val : '0.00';
}
	/*New Registered Latest 5 User*/
public function iN_NewRegisteredUsers() {
        $rows = DB::all("SELECT * FROM i_users ORDER BY iuid DESC LIMIT 5");
        return !empty($rows) ? $rows : null;
}
	/*Latest 5 Subscriptions List*/
public function iN_LatestPaymentsSubscriptionsList() {
        $sql = "SELECT DISTINCT
            S.subscription_id, S.iuid_fk, S.subscribed_iuid_fk, S.subscriber_name, S.plan_id, S.plan_amount,S.admin_earning, S.plan_amount_currency, S.created, S.status, U.iuid, U.i_username, U.i_user_fullname
            FROM i_users U FORCE INDEX(ixForceUser)
            INNER JOIN i_user_subscriptions S FORCE INDEX(ix_Subscribe)
            ON S.subscribed_iuid_fk = U.iuid AND U.uStatus IN('1','3')
            WHERE S.status = 'active' ORDER BY S.subscription_id DESC LIMIT 5";
        $rows = DB::all($sql);
        return !empty($rows) ? $rows : null;
}
	/*Latest Content Payments */
public function iN_LatestContentPaymentsList() {
        $sql = "SELECT DISTINCT
            P.payment_id,P.payer_iuid_fk, P.payed_iuid_fk, P.payed_post_id_fk, P.payed_profile_id_fk, P.order_key, P.payment_type, P.payment_option, P.payment_time, P.payment_status, P.amount, P.fee, P.admin_earning, P.user_earning, U.iuid, U.i_username, U.i_user_fullname
            FROM i_users U FORCE INDEX(ixForceUser)
            INNER JOIN i_user_payments P FORCE INDEX(ixPayment)
            ON P.payed_iuid_fk = U.iuid AND U.uStatus IN('1','3')
            WHERE P.payment_status = 'ok' ORDER BY P.payment_id DESC LIMIT 5";
        $rows = DB::all($sql);
        return !empty($rows) ? $rows : null;
}
	/*Update Site Configurations*/
public function iN_UpdateSiteConfiguration($userID, $watermark, $updateSiteLogo, $updateSiteFavicon, $updateSiteKeywords, $updateSiteDescription, $updateSiteTitle, $updateSiteName) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET site_watermark_logo = ?, site = ?, site_title = ?, site_keywords = ?, site_description = ?, site_logo = ?, site_favicon = ? WHERE configuration_id = 1",
                [(string)$watermark,(string)$updateSiteName,(string)$updateSiteTitle,(string)$updateSiteKeywords,(string)$updateSiteDescription,(string)$updateSiteLogo,(string)$updateSiteFavicon]
            );
            return true;
        } else { return false; }
}
	/*Update Site Business Informations*/
public function iN_UpdateSiteBusinessInformations($userID, $updateSiteCampanyName, $updateSiteCountry, $updateSiteCity, $updateSiteBusinessAddress, $updateSitePostCode, $updateSiteVAT) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET campany = ?, country = ?, city = ?, business_address = ?, post_code = ?, vat = ? WHERE configuration_id = 1",
                [(string)$updateSiteCampanyName,(string)$updateSiteCountry,(string)$updateSiteCity,(string)$updateSiteBusinessAddress,(string)$updateSitePostCode,(string)$updateSiteVAT]
            );
            return true;
        } else { return false; }
}
	/*Update Site Limit Values*/
public function iN_UpdateLimitValues($userID,
    $friendActivityShowTimeLimit,
    $friendActivityShowLimit,
    $TrendPostShowLimit,
    $sugProductShowLimit,
    $sugUserShowLimit,
    $oneSignalStatus,
    $oneSignalApiKey,
    $oneSignalRestApiKey,
    $reCaptchaStatus,
    $reCaptchaSiteKey,
    $reCaptchaSecretKey,
    $postCreateLimit,
    $blockCountryStatus,
    $fileLimit,
    $lengthLimit,
    $postShowLimit,
    $paginatonLimit,
    $approvalFileExtension,
    $availableUploadFileExtensions,
    $ffmpegPath,
    $ffprobePath,
    $unavailableUserNames,
    $messageScrollNumber,
    $adsShowLimit,
    $reelsFeatureStatus,
    $maxVideoDuration
) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations
                SET activity_show_time_limit=?, activity_show_limit=?, howManyDaysTrend=?,
                    showingNumberOfProduct=?, showingNumberOfSuggestedUser=?,
                    g_recaptcha_status=?, g_recaptcha_site_key=?, g_recaptcha_secret_key=?,
                    normal_user_can_post=?, available_verification_file_extensions=?, available_file_extensions=?,
                    available_file_size=?, available_length=?, load_more_limit=?, pagination_limit=?,
                    ffmpeg_path=?, ffmpeg_probe=?, disallowed_usernames=?,
                    one_signal_api=?, one_signal_rest_api=?, one_signal_status=?,
                    load_more_message_limit=?, showingNumberOfAds=?,
                    reels_feature_status=?, max_video_duration=?
                WHERE configuration_id=1",
                [ (int)$friendActivityShowTimeLimit, (int)$friendActivityShowLimit, (int)$TrendPostShowLimit,
                  (int)$sugProductShowLimit, (int)$sugUserShowLimit,
                  (string)$reCaptchaStatus, (string)$reCaptchaSiteKey, (string)$reCaptchaSecretKey,
                  (string)$postCreateLimit, (string)$approvalFileExtension, (string)$availableUploadFileExtensions,
                  (string)$fileLimit, (string)$lengthLimit, (string)$postShowLimit, (int)$paginatonLimit,
                  (string)$ffmpegPath, (string)$ffprobePath, (string)$unavailableUserNames,
                  (string)$oneSignalApiKey, (string)$oneSignalRestApiKey, (string)$oneSignalStatus,
                  (string)$messageScrollNumber, (string)$adsShowLimit,
                  (string)$reelsFeatureStatus, (string)$maxVideoDuration ]
            );
            return true;
        } else { return false; }
}
	/*Update Default Language*/
public function iN_UpdateDefaultLanguage($userID, $lang) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckLangIDExist($lang)) {
            $langKey = $this->iN_CheckLangIDExist($lang);
            DB::exec("UPDATE i_configurations SET default_language = ? WHERE configuration_id = 1", [(string)$langKey]);
            return true;
        } else { return false; }
}
	/*Update Mantenance Mod*/
public function iN_UpdateMaintenanceStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET maintenance_mode = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else { return false; }
}
	/*Update Email Send Mod*/
    public function iN_UpdateEmailSendStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET emailSendStatus = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Register Mod*/
    public function iN_UpdateRegisterStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET register = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update IP Limit Mod*/
    public function iN_UpdateIpLimitStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET ip_limit = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Email Settings*/
    public function iN_UpdateEmailSettings($userID, $updateSmtpEmail, $updateSmtpMail, $updateSmtpEncription, $updateSmtpHost, $updateSmtpUsername, $updateSmtpPassword, $updateSmtpPort) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET default_mail = ?, smtp_or_mail = ?, smtp_encryption = ?, smtp_host = ?, smtp_username = ?, smtp_password = ?, smtp_port = ? WHERE configuration_id = 1",
                [(string)$updateSmtpEmail,(string)$updateSmtpMail,(string)$updateSmtpEncription,(string)$updateSmtpHost,(string)$updateSmtpUsername,(string)$updateSmtpPassword,(string)$updateSmtpPort]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Update AMAZON S3 Settings*/
    public function iN_UpdateAmazonS3Details($userID, $updateS3Region, $updateS3Bucket, $updateS3Key, $updateS3SecretKey, $updateS3Status) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::begin();
            try {
                DB::exec("UPDATE i_configurations SET ocean_status = '0' WHERE configuration_id = 1");
                DB::exec("UPDATE i_configurations SET s3_region = ?, s3_bucket = ?, s3_key = ?, s3_secret_key = ?, s3_status = ? WHERE configuration_id = 1",
                    [(string)$updateS3Region,(string)$updateS3Bucket,(string)$updateS3Key,(string)$updateS3SecretKey,(string)$updateS3Status]
                );
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        } else {
            return false;
        }
    }
	/*Update AMAZON S3 Settings*/
    public function iN_UpdateWasabiDetails($userID, $updateWasRegion, $updateWasBucket, $updateWasKey, $updateWasSecretKey, $updateWasStatus) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::begin();
            try {
                DB::exec("UPDATE i_configurations SET ocean_status = '0' WHERE configuration_id = 1");
                DB::exec("UPDATE i_configurations SET was_region = ?, was_bucket = ?, was_key = ?, was_secret_key = ?, was_status = ? WHERE configuration_id = 1",
                    [(string)$updateWasRegion,(string)$updateWasBucket,(string)$updateWasKey,(string)$updateWasSecretKey,(string)$updateWasStatus]
                );
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        } else {
            return false;
        }
    }
    /*Update MinIO (S3-compatible) Settings*/
    public function iN_UpdateMinioDetails($userID, $endpoint, $region, $bucket, $key, $secret, $publicBase, $pathStyle, $sslVerify, $status) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::begin();
            try {
                DB::exec(
                    "UPDATE i_configurations SET minio_endpoint = ?, minio_region = ?, minio_bucket = ?, minio_key = ?, minio_secret_key = ?, minio_public_base = ?, minio_path_style = ?, minio_ssl_verify = ?, minio_status = ? WHERE configuration_id = 1",
                    [
                        (string)$endpoint,
                        (string)$region,
                        (string)$bucket,
                        (string)$key,
                        (string)$secret,
                        (string)$publicBase,
                        (string)$pathStyle,
                        (string)$sslVerify,
                        (string)$status
                    ]
                );
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        } else {
            return false;
        }
    }
	/*Waiting Approval or Approved Posts*/
public function iN_aWaitingApprovalOrApprovedPostsList($userID, $paginationLimit, $page) {
    $userID = (int)$userID;
    $paginationLimit = (int)$paginationLimit;
    $page = (int)$page;
    $start_from = ($page - 1) * $paginationLimit;

    if ($this->iN_CheckIsAdmin($userID) == 1) {
        $sql = "SELECT P.*, U.iuid, U.i_username, U.i_user_fullname, U.user_avatar, U.user_gender, U.last_login_time, U.user_verified_status
                FROM i_posts AS P
                INNER JOIN i_users AS U ON P.post_owner_id = U.iuid
                WHERE P.post_status IN ('1', '2') AND P.who_can_see = '4'
                ORDER BY CASE WHEN P.post_status = '2' THEN 0 ELSE 1 END, P.post_id DESC
                LIMIT $start_from, $paginationLimit";
        $rows = DB::all($sql);
        return !empty($rows) ? $rows : null;
    }
}

	/*Calculate Non Approved Posts*/
public function iN_CalculateNonApprovedPosts() {
        $val = DB::col("SELECT COUNT(*) FROM i_posts WHERE post_status = '2' AND who_can_see = '4'");
        return $val ? (string)$val : '0';
}
	/*Calculate All Posts*/
    public function iN_CalculateAllPosts() {
        $val = DB::col("SELECT COUNT(*) FROM i_posts");
        return $val ? (string)$val : '0';
    }
	/*Calculate All Storie Posts*/
    public function iN_CalculateAllStoriePosts() {
        $val = DB::col("SELECT COUNT(*) FROM i_user_stories");
        return $val ? (string)$val : '0';
    }
	/*Calculate All Questions*/
    public function iN_CalculateAllQuestions() {
        $val = DB::col("SELECT COUNT(*) FROM i_contacts");
        return $val ? (string)$val : '0';
    }
	/*Calculate All Reported Posts*/
    public function iN_CalculateAllUnReadReportedPosts() {
        $val = DB::col("SELECT COUNT(*) FROM i_post_reports WHERE report_status = '0'");
        return $val ? (string)$val : '0';
    }
	/*Calculate All Reported Comments*/
    public function iN_CalculateAllUnReadReportedComments() {
        $val = DB::col("SELECT COUNT(*) FROM i_comment_reports WHERE report_status = '0'");
        return $val ? (string)$val : '0';
    }
	/*Calculate All Premium Posts*/
    public function iN_CalculateAllPremiumPosts() {
        $val = DB::col("SELECT COUNT(*) FROM i_posts WHERE who_can_see = '4' AND post_status = '1'");
        return $val ? (string)$val : '0';
    }
	/*Calculate All Subscribers Posts*/
    public function iN_CalculateAllSubscribersPosts() {
        $val = DB::col("SELECT COUNT(*) FROM i_posts WHERE who_can_see = '3' AND post_status = '1'");
        return $val ? (string)$val : '0';
    }
	/*Approve / Reject / Decline Premium Post*/
public function iN_UpdateApprovePostStatus($userID, $postDescription, $postNewPoint, $postApproveStat, $postID, $postOwnerID, $approveNot) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckUserExist($postOwnerID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            $time = time();
            $stat = '1';
            $notType = 'accepted_post';
            if ($postApproveStat != '1') {
                $stat = '2';
            }
            if ($postApproveStat == '2') {
                $notType = 'rejected_post';
            }
            if ($postApproveStat == '3') {
                $notType = 'declined_post';
            }
            try {
                DB::begin();
                DB::exec("UPDATE i_posts SET post_text = ?, post_wanted_credit = ?, post_status = ? WHERE post_id = ?",
                    [(string)$postDescription, (string)$postNewPoint, (string)$stat, (int)$postID]
                );
                DB::exec("INSERT INTO i_approve_post_notification (approved_post_id, approved_post_owner_id, approve_status, approve_not, appprove_time) VALUES (?,?,?,?,?)",
                    [(int)$postID, (int)$postOwnerID, (string)$postApproveStat, (string)$approveNot, $time]
                );
                DB::exec("INSERT INTO i_user_notifications (not_post_id, not_not_type, not_time, not_own_iuid, not_iuid) VALUES (?,?,?,?,?)",
                    [(int)$postID, (string)$notType, $time, (int)$postOwnerID, (int)$userID]
                );
                DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE iuid = ?", [(int)$postOwnerID]);
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        } else {
            return false;
        }
    }
    public function iN_UpdatePostDetailsAdmin($userID, $postDescription, $editedPostID, $editedPostOwnerID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckUserExist($editedPostOwnerID) == 1 && $this->iN_CheckPostIDExist($editedPostID) == 1) {
            $time = time();
            DB::exec("UPDATE i_posts SET post_text = ? WHERE post_id = ?", [(string)$postDescription, (int)$editedPostID]);
            return true;
        } else {
            return false;
        }
    }
	/*Delete Post*/
	public function iN_DeletePremiumPostBeforeApprove($userID, $postID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
			$getPostFileIDs = $this->iN_GetAllPostDetails($postID);
			$postFileIDs = isset($getPostFileIDs['post_file']) ? $getPostFileIDs['post_file'] : NULL;
			if ($postFileIDs) {
				$trimValue = rtrim($postFileIDs, ',');
				$explodeFiles = explode(',', $trimValue);
				$explodeFiles = array_unique($explodeFiles);
				foreach ($explodeFiles as $explodeFile) {
					$theFileID = $this->iN_GetUploadedFileDetails($explodeFile);
					$uploadedFileID = isset($theFileID['upload_id']) ? $theFileID['upload_id'] : NULL;
					$uploadedFilePath = isset($theFileID['uploaded_file_path']) ? $theFileID['uploaded_file_path'] : NULL;
					$uploadedFilePathX = isset($theFileID['uploaded_x_file_path']) ? $theFileID['uploaded_x_file_path'] : NULL;
					@unlink('../../../' . $uploadedFilePath);
					@unlink('../../../' . $uploadedFilePathX);
					DB::exec("DELETE FROM i_user_uploads WHERE upload_id = ? AND iuid_fk = ?", [(int)$uploadedFileID, (int)$userID]);
				}
			}
			DB::exec("DELETE FROM i_posts WHERE post_id = ?", [(int)$postID]);
			return true;
		} else {
			return false;
		}
	}
	/*All Posts*/
	public function iN_AllTypePostsList($userID, $paginationLimit, $page) {
		$userID = (int)$userID;
		$paginationLimit = (int)$paginationLimit;
		$page = (int)$page;
		$start_from = ($page - 1) * $paginationLimit;
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			$sql = "SELECT P.post_id,P.shared_post_id,P.post_pined,P.post_owner_id,P.post_text,P.post_file,P.post_created_time,P.who_can_see,P.post_want_status,P.post_wanted_credit,P.url_slug,P.post_status,P.comment_status,U.iuid,U.i_username,U.i_user_fullname,U.user_avatar,U.user_gender,U.last_login_time,U.user_verified_status
			FROM i_posts P FORCE INDEX(ixForcePostOwner)
				INNER JOIN i_users U FORCE INDEX(ixForceUser)
				ON P.post_owner_id = U.iuid
			WHERE P.post_status IN('0','1','2') ORDER BY P.post_id DESC LIMIT $start_from, $paginationLimit";
			$rows = DB::all($sql);
			return !empty($rows) ? $rows : null;
		}
	}
	/*All Premium Posts*/
	public function iN_AllPremiumTypePostsList($userID, $paginationLimit, $page) {
		$userID = (int)$userID;
		$paginationLimit = (int)$paginationLimit;
		$page = (int)$page;
		$start_from = ($page - 1) * $paginationLimit;
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			$sql = "SELECT P.post_id,P.shared_post_id,P.post_pined,P.post_owner_id,P.post_text,P.post_file,P.post_created_time,P.who_can_see,P.post_want_status,P.post_wanted_credit,P.url_slug,P.post_status,P.comment_status,U.iuid,U.i_username,U.i_user_fullname,U.user_avatar,U.user_gender,U.last_login_time,U.user_verified_status
			FROM i_posts P FORCE INDEX(ixForcePostOwner)
				INNER JOIN i_users U FORCE INDEX(ixForceUser)
				ON P.post_owner_id = U.iuid
			WHERE P.post_status IN('1') AND P.who_can_see = '4' ORDER BY P.post_id DESC LIMIT $start_from, $paginationLimit";
			$rows = DB::all($sql);
			return !empty($rows) ? $rows : null;
		}
	}
	/*All Subscribers Posts*/
	public function iN_AllSubscribersTypePostsList($userID, $paginationLimit, $page) {
		$userID = (int)$userID;
		$paginationLimit = (int)$paginationLimit;
		$page = (int)$page;
		$start_from = ($page - 1) * $paginationLimit;
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			$sql = "SELECT P.post_id,P.shared_post_id,P.post_pined,P.post_owner_id,P.post_text,P.post_file,P.post_created_time,P.who_can_see,P.post_want_status,P.post_wanted_credit,P.url_slug,P.post_status,P.comment_status,U.iuid,U.i_username,U.i_user_fullname,U.user_avatar,U.user_gender,U.last_login_time,U.user_verified_status
			FROM i_posts P FORCE INDEX(ixForcePostOwner)
				INNER JOIN i_users U FORCE INDEX(ixForceUser)
				ON P.post_owner_id = U.iuid
			WHERE P.post_status IN('1') AND P.who_can_see = '3' ORDER BY P.post_id DESC LIMIT $start_from, $paginationLimit";
			$rows = DB::all($sql);
			return !empty($rows) ? $rows : null;
		}
	}
	/*Get Custom Codes*/
	public function iN_GetCustomCodes($cID) {
		$row = DB::one("SELECT custom_code FROM i_custom_codes WHERE custom_id = ?", [(int)$cID]);
		return $row ? $row['custom_code'] : false;
	}
	/*Update Custom*/
	public function iN_UpdateCustomCodes($userID, $customCssCode, $cID) {
		$cID = (int)$cID;
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("UPDATE i_custom_codes SET custom_code = ? WHERE custom_id = ?", [(string)$customCssCode, $cID]);
			return true;
		} else {
			return false;
		}
	}
	/*All SVG Icons List*/
	public function iN_AllSVGIcons() {
		$rows = DB::all("SELECT * FROM i_svg_icons");
		return !empty($rows) ? $rows : null;
	}
	/*Get Custom Codes*/
	public function iN_GetSVGCodeFromID($cID) {
		$row = DB::one("SELECT icon_code FROM i_svg_icons WHERE icon_id = ?", [(int)$cID]);
		return $row ? $row['icon_code'] : false;
	}
	/*Update SVG Code*/
	public function iN_UpdateSVGCode($userID, $cID, $svgCode) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("UPDATE i_svg_icons SET icon_code = ? WHERE icon_id = ?", [(string)$svgCode,(int)$cID]);
			return true;
		} else {
			return false;
		}
	}
	/*Check Icon ID Exist*/
	public function iN_CheckIconIDExist($iconID) {
		return (bool) DB::col("SELECT 1 FROM i_svg_icons WHERE icon_id = ? LIMIT 1", [(int)$iconID]);
	}
	/*Update SVG Icon Status*/
	public function iN_UpdateSVGIconStatus($userID, $mod, $iconID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckIconIDExist($iconID) == 1) {
			DB::exec("UPDATE i_svg_icons SET icon_status = ? WHERE icon_id = ?", [(string)$mod,(int)$iconID]);
			return true;
		} else {
			return false;
		}
	}
	/*Insert New SVG ICON*/
	public function iN_InsertNewSVGCode($userID, $newSVGCode) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("INSERT INTO i_svg_icons(icon_code, icon_status) VALUES(?, '0')", [(string)$newSVGCode]);
			return true;
		}
	}
	/*Update Point Plan*/
	public function iN_UpdatePlanFromID($userID, $planKey, $planPoint, $planAmount, $plandID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckPlanExist($plandID)) {
			DB::exec("UPDATE i_premium_plans SET plan_name_key = ?, plan_amount = ?, amount = ? WHERE plan_id = ?",
				[(string)$planKey,(string)$planPoint,(string)$planAmount,(int)$plandID]
			);
			return true;
		} else {
			return false;
		}
	}
	/*Insert New POINT PLAN*/
	public function iN_InsertNewPointPlan($userID, $planKey, $planPointAmount, $planAmount) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("INSERT INTO i_premium_plans(plan_name_key, plan_amount, amount, plan_status) VALUES(?, ?, ?, '0')",
				[(string)$planKey,(string)$planPointAmount,(string)$planAmount]
			);
			return true;
		} else {
			return false;
		}
	}
	/*Update Plan Status*/
	public function iN_UpdatePlanStatus($userID, $mod, $planID) {
		$planID = (int)$planID;
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckPlanExist($planID)) {
			DB::exec("UPDATE i_premium_plans SET plan_status = ? WHERE plan_id = ?", [(string)$mod, $planID]);
			return true;
		} else {
			return false;
		}
	}
	/*Update Frame Plan Status*/
	public function iN_UpdateFramePlanStatus($userID, $mod, $planID) {
		$planID = (int)$planID;
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckFramePlanExist($planID)) {
			DB::exec("UPDATE i_frames SET f_frame_status = ? WHERE f_id = ?", [(string)$mod, $planID]);
			return true;
		} else {
			return false;
		}
	}
	/*Premium Plan List*/
	public function iN_PremiumPlansListFromAdmin() {
		$rows = DB::all("SELECT * FROM i_premium_plans");
		return !empty($rows) ? $rows : null;
	}
	/*Delete Plan*/
	public function iN_DeletePlanFromData($userID, $planID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckPlanExist($planID) == 1) {
			DB::exec("DELETE FROM i_premium_plans WHERE plan_id = ?", [(int)$planID]);
			return true;
		} else {
			return false;
		}
	}
	public function iN_LanguagesList() {
		$rows = DB::all("SELECT * FROM i_langs");
		return !empty($rows) ? $rows : null;
	}
	/*Get Language Details From ID*/
	public function iN_GetLangDetails($langID) {
		return DB::one("SELECT * FROM i_langs WHERE lang_id = ?", [(int)$langID]);
	}

	public function iN_CheckLangIDExistWithoutStatus($langID) {
		$row = DB::one("SELECT lang_name FROM i_langs WHERE lang_id = ?", [(int)$langID]);
		return $row ? $row['lang_name'] : false;
	}
	/*Update Language Status*/
	public function iN_UpdateLanguageStatus($userID, $mod, $langID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && !empty($this->iN_CheckLangIDExistWithoutStatus($langID))) {
			DB::exec("UPDATE i_langs SET lang_status = ? WHERE lang_id = ?", [(string)$mod, (int)$langID]);
			return true;
		} else {
			return false;
		}
	}
	/*Update Language*/
	public function iN_UpdateLanguageByID($userID, $langKey, $langID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && !empty($this->iN_CheckLangIDExistWithoutStatus($langID))) {
			DB::exec("UPDATE i_langs SET lang_name = ? WHERE lang_id = ?", [(string)$langKey,(int)$langID]);
			return true;
		} else {
			return false;
		}
	}
	/*Add New Language*/
	public function iN_AddNewLanguageFromData($userID, $langKey) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("INSERT INTO i_langs(lang_name, lang_status) VALUES(?, '0')", [(string)$langKey]);
			return true;
		} else {
			return false;
		}
	}
	/*Delete Language*/
	public function iN_DeleteLanguage($userID, $langID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && !empty($this->iN_CheckLangIDExistWithoutStatus($langID))) {
			DB::exec("DELETE FROM i_langs WHERE lang_id = ?", [(int)$langID]);
			return true;
		} else {
			return false;
		}
	}
	/*All Posts*/
public function iN_AllTypeOfUsersList($userID, $paginationLimit, $page, $searchValue) {
		$userID = (int)$userID;
		$paginationLimit = (int)$paginationLimit;
		$page = (int)$page;
		$start_from = ($page - 1) * $paginationLimit;
		$where = '';
		$params = [];
		if (!empty($searchValue)){
           $like = '%'.$searchValue.'%';
           $where = "WHERE (i_user_email LIKE ? OR i_username LIKE ? OR i_user_fullname LIKE ?)";
           $params = [$like,$like,$like];
		}
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			$sql = "SELECT * FROM i_users $where ORDER BY iuid DESC LIMIT $start_from, $paginationLimit";
			$rows = DB::all($sql, $params);
			return !empty($rows) ? $rows : null;
		}
}
	/*Update Some User Profile*/
public function iN_UpdateUserProfile($userID, $updatedUser, $updateVerification, $updateUserType, $updateUserWallet) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckUserExist($updatedUser) == 1) {
			if ($updateVerification == '2') {
				DB::exec("UPDATE i_users SET email_verify_status = 'yes' , verify_key = NULL , user_verified_status = '1', certification_status = '2', validation_status = '2', condition_status = '2', fees_status = '2', payout_status = '2', userType = ?, wallet_points = ? WHERE iuid = ?",
                        [(string)$updateUserType,(string)$updateUserWallet,(int)$updatedUser]);
				// Invalidate user cache
				Cache::delete('user:id:' . (int)$updatedUser);
				return true;
			} else if ($updateVerification == '1') {
				DB::exec("UPDATE i_users SET certification_status = '2', validation_status = '1', userType = ?, wallet_points = ? WHERE iuid = ?",
                        [(string)$updateUserType,(string)$updateUserWallet,(int)$updatedUser]);
				// Invalidate user cache
				Cache::delete('user:id:' . (int)$updatedUser);
				return true;
			} else {
				DB::exec("UPDATE i_users SET certification_status = '0', validation_status = '0', condition_status = '0', fees_status = '0', payout_status = '0', userType = ?, wallet_points = ? WHERE iuid = ?",
                        [(string)$updateUserType,(string)$updateUserWallet,(int)$updatedUser]);
				// Invalidate user cache
				Cache::delete('user:id:' . (int)$updatedUser);
				return true;
			}
		} else {
			return false;
		}
}
	/*Delete User*/
public function iN_DeleteUser($userID, $deleteUserID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckUserExist($deleteUserID) == 1) {
            $uid = (int)$deleteUserID;
            try {
                DB::begin();
                DB::exec("DELETE FROM i_chat_conversations WHERE user_one = ? OR user_two = ?", [$uid,$uid]);
                DB::exec("DELETE FROM i_chat_users WHERE user_one = ? OR user_two = ?", [$uid,$uid]);
                DB::exec("DELETE FROM i_comment_reports WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_friends WHERE fr_one = ? OR fr_two = ?", [$uid,$uid]);
                DB::exec("DELETE FROM i_posts WHERE post_owner_id = ?", [$uid]);
                DB::exec("DELETE FROM i_post_comments WHERE comment_uid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_post_comment_likes WHERE c_like_iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_post_likes WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_post_reports WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_saved_posts WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_sessions WHERE session_uid = ?", [$uid]);
                DB::exec("DELETE FROM i_user_avatars WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_user_blocks WHERE blocker_iuid = ? OR blocked_iuid = ?", [$uid,$uid]);
                DB::exec("DELETE FROM i_user_conversation_uploads WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_user_covers WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_user_notifications WHERE not_iuid = ? OR not_own_iuid = ?", [$uid,$uid]);
                DB::exec("DELETE FROM i_user_subscribe_plans WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_user_subscriptions WHERE iuid_fk = ? OR subscribed_iuid_fk = ?", [$uid,$uid]);
                DB::exec("DELETE FROM i_user_uploads WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_verification_requests WHERE iuid_fk = ?", [$uid]);
                DB::exec("DELETE FROM i_users WHERE iuid = ?", [$uid]);
                DB::commit();
                return true;
            } catch (Throwable $e) {
                DB::rollBack();
                return false;
            }
        } else {
            return false;
        }
    }
	/*Total Creator Verification Requests User*/
    public function iN_TotalVerificationRequests() {
		$val = DB::col("SELECT COUNT(*) FROM i_verification_requests WHERE request_status = '0'");
		return $val ? (int)$val : 0;
    }
	/*All Posts*/
    public function iN_AllVerficationRequestList($userID, $paginationLimit, $page) {
		$userID = (int)$userID;
		$paginationLimit = (int)$paginationLimit;
		$page = (int)$page;
		$start_from = ($page - 1) * $paginationLimit;
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			$sql = "SELECT * FROM i_verification_requests WHERE request_status IN('0','1') ORDER BY request_id DESC LIMIT $start_from, $paginationLimit";
			$rows = DB::all($sql);
			return !empty($rows) ? $rows : null;
		}
    }
	/*Get Verification Request Details BY ID*/
    public function iN_GetVerificationRequestFromID($vID) {
		return DB::one("SELECT * FROM i_verification_requests WHERE request_id = ?", [(int)$vID]);
    }
	/*Check Verification Request Exist*/
    public function iN_CheckVerificationRequestExist($vID) {
		return (bool) DB::col("SELECT 1 FROM i_verification_requests WHERE request_id = ? LIMIT 1", [(int)$vID]);
    }
	/*Delete Verification Request*/
    public function iN_DeleteVerificationRequest($userID, $verificationRequestID) {
        $userID = (int)$userID;
        if ($this->iN_CheckIsAdmin($userID) == '1' && $this->iN_CheckVerificationRequestExist($verificationRequestID)) {
            $getUser = $this->iN_GetVerificationRequestFromID($verificationRequestID);
            $iuIDfk = (int)$getUser['iuid_fk'];
            DB::begin();
            try {
                DB::exec("UPDATE i_users SET certification_status = '0' WHERE iuid = ?", [$iuIDfk]);
                DB::exec("DELETE FROM i_verification_requests WHERE request_id = ?", [(int)$verificationRequestID]);
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        }
    }
	/*Update / Insert Approve Verification Request*/
    public function iN_UpdateVerificationProfileStatus($userID, $answerType, $answerValue, $answeringVerificationID) {
        if ($this->iN_CheckIsAdmin($userID) == '1' && $this->iN_CheckVerificationRequestExist($answeringVerificationID) == 1) {
            $data = $this->iN_GetVerificationRequestFromID($answeringVerificationID);
            $iuIDfk = (int)$data['iuid_fk'];
            if ($answerType == '1') {
                DB::begin();
                try {
                    DB::exec("UPDATE i_verification_requests SET request_status = '1', request_not = ? WHERE request_id = ?", [(string)$answerValue,(int)$answeringVerificationID]);
                    DB::exec("UPDATE i_users SET certification_status = '1', validation_status = '1' WHERE iuid = ?", [$iuIDfk]);
                    DB::commit();
                    return true;
                } catch (Throwable $e) { DB::rollBack(); return false; }
            } else if ($answerType == '2') {
                DB::begin();
                try {
                    DB::exec("UPDATE i_verification_requests SET request_status = '2', request_not = ? WHERE request_id = ?", [(string)$answerValue,(int)$answeringVerificationID]);
                    DB::exec("UPDATE i_users SET certification_status = '0', validation_status = '0', condition_status = '0', fees_status = '0', payout_status = '0', payout_method = NULL, paypal_email = NULL, bank_account = NULL WHERE iuid = ?", [$iuIDfk]);
                    DB::commit();
                    return true;
                } catch (Throwable $e) { DB::rollBack(); return false; }
            }
        } else {
            return false;
        }
    }
	/*Check User Have Verification Request*/
    public function iN_CheckUserHasVerificationRequest($userID) {
        $userID = (int)$userID;
        if ($this->iN_CheckUserExist($userID)) {
            return DB::one("SELECT * FROM i_verification_requests WHERE iuid_fk = ?", [$userID]);
        } else {
            return false;
        }
    }
	/*Update Read Status*/
    public function iN_UpdateVerificationAnswerReadStatus($userID) {
        $userID = (int)$userID;
        if ($this->iN_CheckUserExist($userID)) {
            DB::exec("UPDATE i_verification_requests SET user_read_status = '1' WHERE iuid_fk = ?", [$userID]);
        } else {
            return false;
        }
    }
	/*GET ALL PAGE DETAILS*/
    public function iN_GetPageDetails($pageID) {
        return DB::one("SELECT * FROM i_pages WHERE page_id = ?", [(int)$pageID]);
    }
	/*Update Page Details*/
    public function iN_SavePageEdit($userID, $pageTitle, $pageSeoUrl, $pageEditor, $pageID) {
        if ($this->iN_CheckIsAdmin($userID) == '1' && $this->iN_CheckpageExistByID($pageID)) {
            DB::exec("UPDATE i_pages SET page_title = ?, page_name = ?, page_inside = ? WHERE page_id = ?",
                [(string)$pageTitle,(string)$pageSeoUrl,(string)$pageEditor,(int)$pageID]
            );
            return true;
        }
    }
	/*Create a New Page*/
    public function iN_CreateANewPage($userID, $pageTitle, $pageSeoUrl, $pageEditor) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $time = time();
            DB::exec("INSERT INTO i_pages(page_title, page_name, page_created_time, page_inside) VALUES(?,?,?,?)",
                [(string)$pageTitle,(string)$pageSeoUrl,$time,(string)$pageEditor]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Delete Post*/
    public function iN_DeletePage($userID, $pageID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckpageExistByID($pageID) == 1) {
            DB::exec("DELETE FROM i_pages WHERE page_id = ?", [(int)$pageID]);
            return true;
        } else {
            return false;
        }
    }
	/*Check User Email or IP address Sended Contact Email Before*/
    public function iN_CheckAlreadyHaveMail($contacterEmail, $ip) {
        return (bool) DB::col("SELECT 1 FROM i_contacts WHERE (contact_email = ? OR contact_ip = ?) AND contact_read_status = '0' LIMIT 1", [(string)$contacterEmail, (string)$ip]);
    }
	/*Insert New Contact Email*/
    public function iN_InsertUserContactMessage($contacterFullName, $contacterEmail, $contactMessage, $ip) {
        $time = time();
        DB::exec("INSERT INTO i_contacts(contact_full_name, contact_email, contact_message, contact_time, contact_ip, contact_read_status) VALUES(?,?,?,?,?,'0')",
            [(string)$contacterFullName,(string)$contacterEmail,(string)$contactMessage,$time,(string)$ip]
        );
        return true;
    }
	/*All Stickers*/
    public function iN_AllStickersList($userID, $paginationLimit, $page) {
        $userID = (int)$userID;
        $paginationLimit = (int)$paginationLimit;
        $page = (int)$page;
        $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $sql = "SELECT * FROM i_stickers ORDER BY sticker_id DESC LIMIT $start_from, $paginationLimit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Get Sticker Details From ID*/
    public function iN_GetStickerDetailsFromID($sID) {
        return DB::one("SELECT * FROM i_stickers WHERE sticker_id = ?", [(int)$sID]);
    }
	/*Update Sticker URL*/
    public function iN_UpdateStickerURL($userID, $stickerUrl, $sID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckStickerIDExist($sID) == 1) {
            DB::exec("UPDATE i_stickers SET sticker_url = ? WHERE sticker_id = ?", [(string)$stickerUrl, (int)$sID]);
            return true;
        } else {
            return false;
        }
    }
	/*Delete Sticker*/
    public function iN_DeleteSticker($userID, $stickerid) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckStickerIDExist($stickerid) == 1) {
            DB::exec("DELETE FROM i_stickers WHERE sticker_id = ?", [(int)$stickerid]);
            return true;
        } else {
            return false;
        }
    }
	/*Insert New Sticker URL*/
    public function iN_InsertNewStickerURL($userID, $newStickerUrl) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("INSERT INTO i_stickers(sticker_url) VALUES(?)", [(string)$newStickerUrl]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Sticker Status*/
    public function iN_UpdateStickerStatus($userID, $mode, $sID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_stickers SET sticker_status = ? WHERE sticker_id = ?", [(string)$mode, (int)$sID]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Payment Settings*/
    public function iN_UpdatePaymentSettings($userID, $minTipAmount,$defaultSubsType, $defaultCurrency, $comissionFee, $minimumSubscriptionAmountWeekly, $minimumSubscriptionAmountMonthly, $minimumSubscriptionAmountYearly, $minimumPointAmount, $maximumPointAmount, $pointToMoney, $minWihDrawlAmount , $minFeePointWeekly, $minFeePointMonthly, $minFeePointYearly) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET min_tip_amount = ?, subscription_type = ?, default_currency = ?, fee = ?, sub_weekly_minimum_amount = ?, sub_monthly_minimum_amount = ?, sub_yearly_minimum_amount = ?, min_point_limit = ?, max_point_limit = ?, one_point = ?, minimum_withdrawal_amount = ?, min_point_fee_weekly = ?, min_point_fee_monthly = ?, min_point_fee_yearly = ? WHERE configuration_id = 1",
                [ (string)$minTipAmount,(string)$defaultSubsType,(string)$defaultCurrency,(string)$comissionFee,(string)$minimumSubscriptionAmountWeekly,(string)$minimumSubscriptionAmountMonthly,(string)$minimumSubscriptionAmountYearly,(string)$minimumPointAmount,(string)$maximumPointAmount,(string)$pointToMoney,(string)$minWihDrawlAmount,(string)$minFeePointWeekly,(string)$minFeePointMonthly,(string)$minFeePointYearly ]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Update PayPal Sendbox Mode*/
    public function iN_UpdatePayPalSendBoxMode($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET paypal_payment_mode = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update PayPal Status*/
    public function iN_UpdatePayPalStatus($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET paypal_active_pasive = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update PayPal Details*/
    public function iN_UpdatePayPalDetails($userID, $sandBoxEmail, $paypalProductEmail, $paypalCurrency) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET paypal_sendbox_business_email = ?, paypal_product_business_email = ?, paypal_crncy = ? WHERE payment_method_id = 1",
                [(string)$sandBoxEmail,(string)$paypalProductEmail,(string)$paypalCurrency]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Update BitPay Sendbox Mode*/
    public function iN_UpdateBitPaySendBoxMode($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET bitpay_payment_mode = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update BitPay Status*/
    public function iN_UpdateBitPayStatus($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET bitpay_active_pasive = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update PayPal Details*/
    public function iN_UpdateBitPayDetails($userID, $bitNotificationEmail, $bitPassword, $bitPairingCode, $bitLabel, $bitCurrency) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET bitpay_notification_email = ?, bitpay_password = ?, bitpay_pairing_code = ?, bitpay_label = ?, bitpay_crncy = ? WHERE payment_method_id = 1",
                [(string)$bitNotificationEmail,(string)$bitPassword,(string)$bitPairingCode,(string)$bitLabel,(string)$bitCurrency]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Update Stripe Sendbox Mode*/
    public function iN_UpdateStripeSendBoxMode($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET stripe_payment_mode = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Stripe Status*/
    public function iN_UpdateStripeStatus($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET stripe_active_pasive = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Stripe Details*/
    public function iN_UpdateStripeDetails($userID, $stTestSecretKey, $stTestPublicKey, $stLiveSecretKey, $stLivePublicKey, $stCurrency) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET stripe_test_secret_key = ?, stripe_test_public_key = ?, stripe_live_secret_key = ?, stripe_live_public_key = ?, stripe_crncy = ? WHERE payment_method_id = 1",
                [(string)$stTestSecretKey,(string)$stTestPublicKey,(string)$stLiveSecretKey,(string)$stLivePublicKey,(string)$stCurrency]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Update Stripe Sendbox Mode*/
    public function iN_UpdateAuthorizeNetSendBoxMode($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET authorize_payment_mode = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Stripe Status*/
    public function iN_UpdateAuthorizeNetStatus($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET authorizenet_active_pasive = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update AuthorizeNet Details*/
    public function iN_UpdateAuthorizeNetDetails($userID, $autTestAppID, $autTestTransactionKey, $autLiveAppID, $autLiveTransactionKey, $autCurrency) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET authorizenet_test_ap_id = ?, authorizenet_test_transaction_key = ?, authorizenet_live_api_id = ?, authorizenet_live_transaction_key = ?, authorize_crncy = ? WHERE payment_method_id = 1",
                [(string)$autTestAppID,(string)$autTestTransactionKey,(string)$autLiveAppID,(string)$autLiveTransactionKey,(string)$autCurrency]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Update IyziCo Sendbox Mode*/
    public function iN_UpdateIyziCoSendBoxMode($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET iyzico_payment_mode = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update IyziCo Status*/
    public function iN_UpdateIyziCoStatus($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET iyzico_active_pasive = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update IyziCo Details*/
    public function iN_UpdateIyziCoDetails($userID, $iyziTestSecretKey, $iyziTestApiKey, $iyziLiveApiKey, $iyziLiveApiSeckretKey, $iyziCurrency) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET iyzico_testing_secret_key = ?, iyzico_testing_api_key = ?, iyzico_live_api_key = ?, iyzico_live_secret_key = ?, iyzico_crncy = ? WHERE payment_method_id = 1",
                [(string)$iyziTestSecretKey,(string)$iyziTestApiKey,(string)$iyziLiveApiKey,(string)$iyziLiveApiSeckretKey,(string)$iyziCurrency]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Update RazorPay Sendbox Mode*/
    public function iN_UpdateRazorPaySendBoxMode($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET razorpay_payment_mode = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update RazorPay Status*/
    public function iN_UpdateRazorPayStatus($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET razorpay_active_pasive = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update RazorPay Details*/
    public function iN_UpdateRazorPayDetails($userID, $razorTestKey, $razorTestSecret, $razorLiveKey, $razorLiveSecret, $razorCurrency) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET razorpay_testing_key_id = ?, razorpay_testing_secret_key = ?, razorpay_live_key_id = ?, razorpay_live_secret_key = ?, razorpay_crncy = ? WHERE payment_method_id = 1",
                [(string)$razorTestKey,(string)$razorTestSecret,(string)$razorLiveKey,(string)$razorLiveSecret,(string)$razorCurrency]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Update PayStack Sendbox Mode*/
    public function iN_UpdatePayStackSendBoxMode($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET paystack_payment_mode = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update PayStack Status*/
    public function iN_UpdatePayStackStatus($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET paystack_active_pasive = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update PayStack Details*/
    public function iN_UpdatePayStackDetails($userID, $payStackTestSecret, $payStackTestPublic, $payStackLiveSecret, $payStackLivePublic, $payStackCurrency) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET paystack_testing_secret_key = ?, paystack_testing_public_key = ?, paystack_live_secret_key = ?, pay_stack_liive_public_key = ?, paystack_crncy = ? WHERE payment_method_id = 1",
                [(string)$payStackTestSecret,(string)$payStackTestPublic,(string)$payStackLiveSecret,(string)$payStackLivePublic,(string)$payStackCurrency]
            );
            return true;
        } else {
            return false;
        }
    }
    public function iN_UpdateSocialLoginDetails($userID, $GoogleCliendID, $TwitterCliendID, $GoogleIcon, $TwitterIcon, $GoogleCliendSecret, $TwitterCliendSecret, $GoogleSocialLoginStatus, $TwitterSocialLoginStatus) {

        if ($this->iN_CheckIsAdmin($userID) == 1) {
            // Normalize to nulls and ints
            $gKey = $GoogleCliendID !== '' ? (string)$GoogleCliendID : null;
            $gSecret = $GoogleCliendSecret !== '' ? (string)$GoogleCliendSecret : null;
            $gIconVal = $GoogleIcon !== '' ? (string)$GoogleIcon : null;
            $gStatus = !empty($GoogleSocialLoginStatus) ? (string)$GoogleSocialLoginStatus : '0';

            $tKey = $TwitterCliendID !== '' ? (string)$TwitterCliendID : null;
            $tSecret = $TwitterCliendSecret !== '' ? (string)$TwitterCliendSecret : null;
            $tIconVal = $TwitterIcon !== '' ? (string)$TwitterIcon : null;
            $tStatus = !empty($TwitterSocialLoginStatus) ? (string)$TwitterSocialLoginStatus : '0';

            DB::exec("UPDATE i_social_logins SET s_key_one = ?, s_key_two = ?, s_icon = ?, s_status = ? WHERE s_id = 1",
                [$gKey, $gSecret, $gIconVal, $gStatus]
            );
            DB::exec("UPDATE i_social_logins SET s_key_one = ?, s_key_two = ?, s_icon = ?, s_status = ? WHERE s_id = 2",
                [$tKey, $tSecret, $tIconVal, $tStatus]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Payments Withdrawal AND Subscription Payments List*/
    public function iN_PayoutWithdrawalAndSubscriptionHistory($userID, $paginationLimit, $page, $type) {
        $userID = (int)$userID;
        $paginationLimit = (int)$paginationLimit;
        $page = (int)$page;
        $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $sql = "SELECT DISTINCT
                        P.payout_id, P.iuid_fk, P.amount, P.method, P.payout_time, P.status, P.payment_type,
                        U.iuid, U.i_username, U.i_user_fullname
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_user_payouts P FORCE INDEX(ix_PayoutUser)
                      ON P.iuid_fk = U.iuid AND U.uStatus IN('1','3')
                    WHERE P.payment_type = ?
                    ORDER BY P.payout_id DESC
                    LIMIT $start_from, $paginationLimit";
            $rows = DB::all($sql, [(string)$type]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Get User Payout Details*/
    public function iN_GetUserPayoutDetails($userID, $ID) {
        $userID = (int)$userID;
        $ID = (int)$ID;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            return DB::one("SELECT * FROM i_user_payouts WHERE payout_id = ?", [$ID]);
        } else {
            return false;
        }
    }
	/*Check Payment ID Exist*/
    public function iN_CheckPaymentRequestIDExist($userID, $declineID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            return (bool) DB::col("SELECT 1 FROM i_user_payouts WHERE payout_id = ? LIMIT 1", [(int)$declineID]);
        } else {
            return false;
        }
    }
	/*Update Payout Status*/
public function iN_UpdatePayoutStatus($userID, $id) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $time = time();
            DB::exec("UPDATE i_user_payouts SET status = 'payed', payout_time = ? WHERE payout_id = ?", [$time, (int)$id]);
            return true;
        } else { return false; }
}
	/*Ok Decline*/
public function iN_DeclineRequest($userID, $declinedID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $uData = $this->iN_GetUserPayoutDetails($userID, $declinedID);
            if (!$uData) { return false; }
            $uDataUserID = (int)$uData['iuid_fk'];
            $uDataAmount = (string)$uData['amount'];
            try {
                DB::begin();
                DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$uDataAmount, $uDataUserID]);
                DB::exec("UPDATE i_user_payouts SET status = 'declined' WHERE payout_id = ?", [(int)$declinedID]);
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        } else { return false; }
}
	/*Delete Post*/
public function iN_DeletePayoutRequest($userID, $ID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("DELETE FROM i_user_payouts WHERE payout_id = ?", [(int)$ID]);
            return true;
        } else { return false; }
}
	/*Insert Activate Code*/
public function iN_InsertNewForgotPasswordCode($userEmail, $code) {
        $affected = DB::exec("UPDATE i_users SET forgot_pass_code = ? WHERE i_user_email = ?", [(string)$code, (string)$userEmail]);
        return $affected > 0;
}
	/*Check Code Exist*/
public function iN_CheckCodeExist($activationCode) {
        return (bool) DB::col("SELECT 1 FROM i_users WHERE forgot_pass_code = ? LIMIT 1", [(string)$activationCode]);
}
public function iN_CheckVerCodeExist($activationCode) {
        return (bool) DB::col("SELECT 1 FROM i_users WHERE verify_key = ? LIMIT 1", [(string)$activationCode]);
}
public function iN_InsertNewVerificationCode($userID, $code){
        $affected = DB::exec("UPDATE i_users SET verify_key = ? WHERE iuid = ?", [(string)$code, (int)$userID]);
        return $affected > 0;
}
	/*Update Password*/
public function iN_ResetPassword($code, $newPassword) {
        try {
            DB::begin();
            DB::exec("UPDATE i_users SET i_password = ? WHERE forgot_pass_code = ? AND uStatus IN('1','3')", [(string)$newPassword, (string)$code]);
            DB::exec("UPDATE i_users SET forgot_pass_code = NULL WHERE forgot_pass_code = ?", [(string)$code]);
            DB::commit();
            return true;
        } catch (Throwable $e) { DB::rollBack(); return false; }
}
	/*Insert New Advertisement*/
public function iN_InsertNewAdvertisement($userID, $adsImage, $adsTitle, $adsDescription, $adsRedirectURL) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("INSERT INTO i_advertisements (ads_title, ads_desc, ads_url, ads_image) VALUES (?,?,?,?)",
                [(string)$adsTitle, (string)$adsDescription, (string)$adsRedirectURL, (string)$adsImage]
            );
            return true;
        }
}
	/*Advertisements List (Admin)*/
public function iN_AdvertisementsListAdmin($userID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $rows = DB::all("SELECT * FROM i_advertisements ORDER BY ads_id");
            return !empty($rows) ? $rows : null;
        }
}
	/*Check Premium Plan Exist*/
public function CheckAdsExist($adsID) {
        return (bool) DB::col("SELECT 1 FROM i_advertisements WHERE ads_id = ? LIMIT 1", [(int)$adsID]);
}
	/*Update Ads Status*/
public function iN_UpdateAdsStatus($userID, $mod, $adsID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckAdsExist($adsID)) {
            DB::exec("UPDATE i_advertisements SET ads_status = ? WHERE ads_id = ?", [(string)$mod, (int)$adsID]);
            return true;
        } else { return false; }
}
	/*Get Ads Details*/
public function iN_GetAdsDetailsAdmin($userID, $adsID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckAdsExist($adsID)) {
            return DB::one("SELECT * FROM i_advertisements WHERE ads_id = ? LIMIT 1", [(int)$adsID]);
        } else { return false; }
}
	/*Insert New Advertisement*/
public function iN_UpdateAdvertisement($userID, $adsID, $adsImage, $adsTitle, $adsDescription, $adsRedirectURL) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_advertisements SET ads_image = ?, ads_title = ?, ads_desc = ?, ads_url = ?, ads_status = '0' WHERE ads_id = ?",
                [(string)$adsImage, (string)$adsTitle, (string)$adsDescription, (string)$adsRedirectURL, (int)$adsID]
            );
            return true;
        }
}
	/*Delete Ads*/
public function iN_DeleteAdsFromData($userID, $adsID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckAdsExist($adsID) == 1) {
            DB::exec("DELETE FROM i_advertisements WHERE ads_id = ?", [(int)$adsID]);
            return true;
        } else { return false; }
}
	/*Show Advertisements If Exist*/
public function iN_ShowAds($numberShow) {
        $limit = (int)$numberShow;
        $rows = DB::all("SELECT * FROM i_advertisements WHERE ads_status = '1' ORDER BY RAND() LIMIT $limit");
        return !empty($rows) ? $rows : null;
}
	public function xss_clean($data) {
		// Fix &entity\n;
		$data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Anti-XSS: Removes IE-only XSS vectors such as 'expression(...)' used within inline style attributes.
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do {
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		} while ($old_data !== $data);

		return $data;
	}
	/*Get Not Rejected or declined  the post*/
    public function iN_GetAdminNot($userID, $postID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            return DB::one("SELECT * FROM i_approve_post_notification WHERE approve_status IN('2','3') AND approved_post_id = ? AND approved_post_owner_id = ? ORDER BY approve_id DESC LIMIT 1",
                [(int)$postID,(int)$userID]
            );
        } else {
            return false;
        }
    }
	/*Payments history List*/
    public function iN_YourPaymentsList($userID, $paginationLimit, $page) {
        $userID = (int)$userID;
        $paginationLimit = (int)$paginationLimit;
        $page = (int)$page;
        $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $sql = "SELECT DISTINCT
            P.payment_id,P.payer_iuid_fk, P.payed_iuid_fk, P.payed_post_id_fk, P.payed_profile_id_fk, P.order_key, P.payment_type, P.payment_option, P.payment_time, P.payment_status, P.amount, P.fee, P.admin_earning, P.user_earning, U.iuid, U.i_username, U.i_user_fullname
            FROM i_users U FORCE INDEX(ixForceUser)
            INNER JOIN i_user_payments P FORCE INDEX(ixPayment)
            ON P.payer_iuid_fk = U.iuid AND U.uStatus IN('1','3')
            WHERE P.payment_status = 'ok' AND P.payer_iuid_fk = ? AND P.payment_type IN('post','live_stream','tips','live_gift','videoCall','boostPost','frame') ORDER BY P.payment_id DESC LIMIT $start_from, $paginationLimit";
            $rows = DB::all($sql, [$userID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*User Total Point Payments*/
    public function iN_UserTotalPointPayments($userID) {
        $userID = (int)$userID;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_payments WHERE payer_iuid_fk = ? AND payment_type = 'post'", [$userID]);
            return $val ? (int)$val : 0;
        }
    }
	/*Check Invoice ID Exist*/
    public function iN_CheckInvoiceIDExist($invoiceID, $userID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            return (bool) DB::col("SELECT 1 FROM i_user_payments WHERE payment_id = ? AND payer_iuid_fk = ? LIMIT 1", [(int)$invoiceID,(int)$userID]);
        }
    }
	/*Get Invoice Details*/
    public function iN_GetInvoiceDetails($invoiceID, $userID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            return DB::one("SELECT * FROM i_user_payments WHERE payment_id = ? AND payer_iuid_fk = ?", [(int)$invoiceID,(int)$userID]);
        } else {
            return false;
        }
    }
	/*Update Subscription Stripe Status*/
    public function iN_UpdateStripeSubStatus($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET stripe_status = ? WHERE configuration_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
    /*Update Subscription Stripe Details*/
public function iN_UpdateSubStripeDetails($userID, $stSubSecretKey, $stSubPublicKey, $stSubCurrency, $webhookSecret = null) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            // Ensure the webhook secret column exists (safe migration-in-place)
            $column = DB::one("SHOW COLUMNS FROM i_configurations LIKE 'stripe_webhook_secret'");
            if (!$column) {
                DB::exec("ALTER TABLE i_configurations ADD stripe_webhook_secret longtext NULL");
            }

            if ($webhookSecret === null) {
                // Backward compatibility: don't touch webhook secret if not provided
                DB::exec(
                    "UPDATE i_configurations SET stripe_secret_key = ?, stripe_public_key = ?, stripe_currency = ? WHERE configuration_id = 1",
                    [(string)$stSubSecretKey, (string)$stSubPublicKey, (string)$stSubCurrency]
                );
            } else {
                DB::exec(
                    "UPDATE i_configurations SET stripe_secret_key = ?, stripe_public_key = ?, stripe_currency = ?, stripe_webhook_secret = ? WHERE configuration_id = 1",
                    [(string)$stSubSecretKey, (string)$stSubPublicKey, (string)$stSubCurrency, (string)$webhookSecret]
                );
            }
            return true;
        } else { return false; }
}
	/*Update Giphy Api Key*/
public function iN_UpdateGiphyAPIKey($userID, $giphyKey) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET giphy_api_key = ? WHERE configuration_id = 1", [(string)$giphyKey]);
            return true;
        } else { return false; }
}
	/*Check Last Finish Time*/
public function iN_GetLiveStreamingDetails($userID) {
        $row = DB::one("SELECT * FROM i_live WHERE live_uid_fk = ? ORDER BY live_id DESC LIMIT 1", [(int)$userID]);
        return $row ?: false;
}
	/*Check Last Finish Time*/
public function iN_GetLiveStreamingDetailsByID($liveID) {
        $row = DB::one("SELECT * FROM i_live WHERE live_id = ? ORDER BY live_id DESC LIMIT 1", [(int)$liveID]);
        return $row ?: false;
}
	/*Check Last Finish Time*/
public function iN_GetLastLiveFinishTime($userID) {
        $row = DB::one("SELECT finish_time FROM i_live WHERE live_uid_fk = ? ORDER BY live_id DESC LIMIT 1", [(int)$userID]);
        return $row ? ($row['finish_time'] ?? false) : false;
}
	/*Create a Free Live Streaming*/
public function iN_CreateAFreeLiveStreaming($userID, $liveStreamingTitle, $freeLiveTime, $channelName) {
        $currentTime = time();
        $finishTime = $currentTime + 60 * $freeLiveTime;
        $l_Time = $this->iN_GetLastLiveFinishTime($userID);
        if ($l_Time) {
            if ($currentTime > $l_Time) {
                DB::exec("INSERT INTO i_live (live_name, started_at, finish_time, live_uid_fk, live_type, live_channel) VALUES (?,?,?,?, 'free', ?)",
                    [(string)$liveStreamingTitle, $currentTime, $finishTime, (int)$userID, (string)$channelName]
                );
                return true;
            } else {
                /*Redirect user from live streaming page*/
                echo '2';
            }
        } else {
            DB::exec("INSERT INTO i_live (live_name, started_at, finish_time, live_uid_fk, live_type, live_channel) VALUES (?,?,?,?, 'free', ?)",
                [(string)$liveStreamingTitle, $currentTime, $finishTime, (int)$userID, (string)$channelName]
            );
            return true;
        }
}
	/*Create a Free Live Streaming*/
	public function iN_CreateAPaidLiveStreaming($userID, $liveStreamingTitle, $freeLiveTime, $channelName, $streamFee) {
		$currentTime = time();
		$finishTime = $currentTime + 60 * $freeLiveTime;
        if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("INSERT INTO i_live(live_name, started_at, finish_time, live_uid_fk, live_type, live_channel, live_credit) VALUES(?,?,?,?, 'paid', ?, ?)",
                [(string)$liveStreamingTitle, $currentTime, $finishTime, (int)$userID, (string)$channelName, (string)$streamFee]
            );
            return true;
        } else {
            return false;
        }
	}
	public function iN_StartCloudRecording($vendor, $region, $bucket, $accessKey, $secretKey, $cname, $uid, $post_id, $agoraAppID, $agoraCustomerID, $agoraCertificate) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.agora.io/v1/apps/" . $agoraAppID . "/cloud_recording/acquire");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($agoraCustomerID . ":" . $agoraCertificate), 'Content-Type: application/json;charset=utf-8'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{
		    "cname": "' . $cname . '",
		    "uid": "' . $uid . '",
		    "clientRequest":{
		    }
		}');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($response);
		$resourceId = $data->resourceId;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.agora.io/v1/apps/" . $agoraAppID . "/cloud_recording/resourceid/" . $resourceId . "/mode/mix/start");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($agoraCustomerID . ":" . $agoraCertificate), 'Content-Type: application/json;charset=utf-8'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{
		    "cname":"' . $cname . '",
		    "uid":"' . $uid . '",
		    "clientRequest":{
		        "recordingConfig":{
		            "channelType":1,
		            "streamTypes":2,
		            "audioProfile":1,
		            "videoStreamType":1,
		            "maxIdleTime":120,
		            "transcodingConfig":{
		                "width":480,
		                "height":720,
		                "fps":24,
		                "bitrate":800,
		                "maxResolutionUid":"1",
		                "mixedVideoLayout":1
		                }
		            },
		        "storageConfig":{
		            "vendor":' . $vendor . ',
		            "region":' . $region . ',
		            "bucket":"' . $bucket . '",
		            "accessKey":"' . $accessKey . '",
		            "secretKey":"' . $secretKey . '",
		            "fileNamePrefix": [
		                "upload",
		                "videos",
		                "' . date('Y') . '",
		                "' . date('m') . '"
		              ]
		        }
		    }
		} ');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($response);
        if (!empty($data->sid) && !empty($resourceId)) {
            DB::exec("UPDATE i_live SET a_resource_id = ?, a_sid = ? WHERE live_id = ?", [(string)$resourceId, (string)$data->sid, (int)$post_id]);
        }
		return true;
	}
	/*Check Live EXISTs*/
    public function iN_CheckLiveIDExist($liveID) {
        return (bool) DB::col("SELECT 1 FROM i_live WHERE live_id = ? LIMIT 1", [(int)$liveID]);
    }
	/*Check User Liked The Post Before*/
    public function iN_CheckLiveLikedBefore($userID, $liveID) {
        return (bool) DB::col("SELECT 1 FROM i_live_likes WHERE live_id_fk = ? AND iuid_fk = ? LIMIT 1", [(int)$liveID, (int)$userID]);
    }
	/*Comment Like Count*/
    public function iN_TotalLiveLiked($liveID) {
        $val = DB::col("SELECT COUNT(*) FROM i_live_likes WHERE live_id_fk = ?", [(int)$liveID]);
        return (int)$val;
    }
	/*Like Post*/
    public function iN_LiveLike($userID, $liveID) {
        $time = time();
        $userIP = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckLiveIDExist($liveID) == 1) {
            if ($this->iN_CheckLiveLikedBefore($userID, $liveID) == 1) {
                DB::exec("DELETE FROM i_live_likes WHERE live_id_fk = ? AND iuid_fk = ?", [(int)$liveID,(int)$userID]);
                return false;
            } else {
                DB::exec("INSERT INTO i_live_likes (live_id_fk,iuid_fk,like_time,user_ip) VALUES(?,?,?,?)", [(int)$liveID,(int)$userID,$time,(string)$userIP]);
                return true;
            }
        }
    }
	/*Get Live Video Online users*/
public function iN_OnlineLiveVideoUserCount($userID, $liveID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckLiveIDExist($liveID) == 1) {
            $time = time();
            DB::exec("UPDATE i_live_video_users SET live_time = ? WHERE live_video_id = ? AND live_user_uid_fk = ?", [$time, (int)$liveID, (int)$userID]);
        }
        $val = DB::col("SELECT COUNT(*) FROM i_live_video_users WHERE live_video_id = ? AND FROM_UNIXTIME(live_time) > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 minute)", [(int)$liveID]);
        return (int)$val;
}
	/*Check Last Finish Time*/
public function iN_GetLastLiveFinishTimeFromID($liveID) {
        $row = DB::one("SELECT finish_time FROM i_live WHERE live_id = ? LIMIT 1", [(int)$liveID]);
        return $row ? ($row['finish_time'] ?? false) : false;
}
public function iN_InsertMyOnlineStatus($userID, $liveID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckLiveIDExist($liveID) == 1) {
            $time = time();
            $exists = (bool) DB::col("SELECT 1 FROM i_live_video_users WHERE live_video_id = ? AND live_user_uid_fk = ? LIMIT 1", [(int)$liveID, (int)$userID]);
            if ($exists) {
                DB::exec("UPDATE i_live_video_users SET live_time = ? WHERE live_video_id = ? AND live_user_uid_fk = ?", [$time, (int)$liveID, (int)$userID]);
            } else {
                DB::exec("INSERT INTO i_live_video_users (live_user_uid_fk, live_time, live_video_id) VALUES (?,?,?)", [(int)$userID, $time, (int)$liveID]);
            }
        }
}
public function iN_CheckUserPurchasedThisLiveStream($userID, $purchaseLiveStreamID) {
        return (bool) DB::col("SELECT 1 FROM i_user_payments WHERE payer_iuid_fk = ? AND payed_live_stream_id_fk = ? LIMIT 1", [(int)$userID, (int)$purchaseLiveStreamID]);
}
	/*Buy Post*/
public function iN_BuyLiveStreaming($userID, $userLiveStreamOwnerID, $purchasdLiveStreamID, $amount, $adminEarning, $userEarning, $fee, $credit) {
        if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckUserExist($userLiveStreamOwnerID) == '1' && $this->iN_CheckLiveIDExist($purchasdLiveStreamID) == '1') {
            $time = time();
            DB::exec("INSERT INTO i_user_payments (payer_iuid_fk, payed_iuid_fk, payed_live_stream_id_fk, payment_type, payment_time, payment_status, amount, fee, admin_earning, user_earning)
                      VALUES (?,?,?, 'live_stream', ?, 'ok', ?, ?, ?, ?)",
                      [(int)$userID, (int)$userLiveStreamOwnerID, (int)$purchasdLiveStreamID, $time, (string)$amount, (string)$fee, (string)$adminEarning, (string)$userEarning]
            );
            DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [(int)$credit, (int)$userID]);
            DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [(string)$userEarning, (int)$userLiveStreamOwnerID]);
            return true;
        }
        return false;
}
	/*Free Live Streamings List*/
    public function iN_LiveStreaminsList($liveStyle, $lastPostID, $showingPost) {
        $tree_minutes_ago = time() - (10 * 1);
        $more = '';
        $params = [(string)$liveStyle, $tree_minutes_ago];
        if ($lastPostID) { $more = ' AND L.live_id < ? '; $params[] = (int)$lastPostID; }
        $showingPost = (int)$showingPost;
        $sql = "SELECT DISTINCT
                L.live_id, L.live_name, L.live_uid_fk, L.finish_time, L.live_credit,
                U.iuid, U.i_username, U.i_user_fullname
            FROM i_live L FORCE INDEX(ix_Live)
            INNER JOIN i_users U FORCE INDEX(ixForceUser)
                ON L.live_uid_fk = U.iuid AND U.uStatus IN('1','3')
            WHERE L.live_type = ? AND L.finish_time >= ? $more
            ORDER BY L.live_id DESC LIMIT $showingPost";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
	/*Update Live Streaming time*/
public function iN_UpdateLiveStreamingTime($liveID) {
    $time = time();
    DB::exec("UPDATE i_live SET finish_time = ? WHERE live_id = ?", [$time, (int)$liveID]);
}
	/*Update Email Settings*/
public function iN_UpdateAgoraLiveStreamingSettings($userID,$freeLiveStreamingStatus,$paidLiveStreamingStatus, $liveStatus, $freeLiveLimit, $agora_AppID, $agora_Certificate, $agora_CustomerID, $liveMinimumFee) {
    if ($this->iN_CheckIsAdmin($userID) == 1) {
        DB::exec("UPDATE i_configurations SET free_live_streaming_status = ?, paid_live_streaming_status = ?, agora_status = ?, agora_app_id = ?, agora_certificate = ?, agora_customer_id = ?, free_live_time = ?, minimum_live_streaming_fee = ? WHERE configuration_id = 1",
            [(string)$freeLiveStreamingStatus,(string)$paidLiveStreamingStatus,(string)$liveStatus,(string)$agora_AppID,(string)$agora_Certificate,(string)$agora_CustomerID,(string)$freeLiveLimit,(string)$liveMinimumFee]
        );
        return true;
    } else {
        return false;
    }
}
	/*Hashtags*/
public function iN_GetHashTagsSearch($hashTag, $lastPostID, $showingPosts) {
        $hashTag = strip_tags(trim($hashTag));
        $hashtags_list = array_filter(array_map('trim', explode(',', $hashTag)));
        $conds = [];
        $params = [];
        foreach ($hashtags_list as $ht) {
            $conds[] = "FIND_IN_SET(LOWER(?), LOWER(hashtags))";
            $params[] = strtolower($ht);
        }
        if (empty($conds)) { return null; }
        $hashtag_query = implode(' AND ', $conds);
        $morequery = '';
        if ($lastPostID) {
            $morequery = ' AND P.post_id < ' . (int)$lastPostID . ' ';
        }
        $showingPosts = (int)$showingPosts;
        $sql = "SELECT DISTINCT P.post_id,P.shared_post_id,P.post_pined,P.comment_status,P.post_owner_id,P.post_text,P.post_file,P.post_created_time,P.who_can_see,P.post_want_status,P.url_slug,P.post_wanted_credit,P.post_status,P.hashtags,
                        U.iuid,U.i_username,U.payout_method, U.thanks_for_tip, U.i_user_fullname,U.user_avatar,U.user_gender,U.last_login_time,U.user_verified_status
                FROM i_friends F FORCE INDEX(ixFriend)
                INNER JOIN i_posts P FORCE INDEX (ixForcePostOwner) ON P.post_owner_id = F.fr_two
                INNER JOIN i_users U FORCE INDEX (ixForceUser) ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3') AND F.fr_status IN('me', 'flwr', 'subscriber')
                WHERE ($hashtag_query) AND P.post_type NOT IN('reels') $morequery ORDER BY P.post_id DESC LIMIT $showingPosts";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
}
public function iN_CaltulateHashFromDatabase($hashTag = "") {
        $hashTag = strip_tags(trim($hashTag));
        $hashtags_list = array_filter(array_map('trim', explode(',', $hashTag)));
        if (empty($hashtags_list)) { return '0'; }
        $conds = [];
        $params = [];
        foreach ($hashtags_list as $ht) {
            $conds[] = "FIND_IN_SET(LOWER(?), LOWER(hashtags))";
            $params[] = strtolower($ht);
        }
        $hashtag_query = implode(' AND ', $conds);
        $sql = "SELECT COUNT(*) FROM i_posts WHERE ($hashtag_query)";
        $val = DB::col($sql, $params);
        return $val ? (string)$val : false;
}
	/*List Question Answer From Landing Page*/
public function iN_ListQuestionAnswerFromLanding() {
        $rows = DB::all("SELECT * FROM i_landing_qa WHERE qa_status = '1'");
        return !empty($rows) ? $rows : null;
}
	/*Popular User From Last Week*/
public function iN_PopularUsersFromLastWeekInExplorePageLanding() {
        $sql = "SELECT DISTINCT P.post_owner_id, U.iuid,U.i_username,U.i_user_fullname,U.user_verified_status, U.user_gender , COUNT(P.post_owner_id) as cnt
                FROM i_posts P FORCE INDEX(ixForcePostOwner)
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3')
                WHERE WEEK(FROM_UNIXTIME(P.post_created_time)) = WEEK(NOW()) - 1
                GROUP BY P.post_owner_id ORDER BY cnt DESC LIMIT 3";
        $rows = DB::all($sql);
        return !empty($rows) ? $rows : null;
}
	/*Update Theme*/
public function iN_UpdateTheme($userID, $theme) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_page_type = ? WHERE configuration_id = 1", [(string)$theme]);
            return true;
        } else {
            return false;
        }
}
	/*Update First Landing Page Image*/
public function iN_UpdateFirstLandingPageImage($userID, $landingImage) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_first_image = ? WHERE configuration_id = 1", [(string)$landingImage]);
            return true;
        } else {
            return false;
        }
}
	/*Update Second Landing Page Image*/
public function iN_UpdateSecondLandingPageImage($userID, $landingImage) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_first_image_arrow = ? WHERE configuration_id = 1", [(string)$landingImage]);
            return true;
        } else {
            return false;
        }
}
	/*Update Third Landing Page Image*/
public function iN_UpdateThirdLandingPageImage($userID, $landingImage) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_feature_image_one = ? WHERE configuration_id = 1", [(string)$landingImage]);
            return true;
        } else {
            return false;
        }
}
	/*Update Fourth Landing Page Image*/
public function iN_UpdateFourthLandingPageImage($userID, $landingImage) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_feature_image_two = ? WHERE configuration_id = 1", [(string)$landingImage]);
            return true;
        } else {
            return false;
        }
}
	/*Update Fourth Landing Page Image*/
public function iN_UpdateFifthLandingPageImage($userID, $landingImage) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_feature_image_three = ? WHERE configuration_id = 1", [(string)$landingImage]);
            return true;
        } else {
            return false;
        }
}
	/*Update Fourth Landing Page Image*/
    public function iN_UpdateSixthLandingPageImage($userID, $landingImage) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_feature_image_four = ? WHERE configuration_id = 1", [(string)$landingImage]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Fourth Landing Page Image*/
    public function iN_UpdateSeventhLandingPageImage($userID, $landingImage) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_feature_image_five = ? WHERE configuration_id = 1", [(string)$landingImage]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Fourth Landing Page Image*/
    public function iN_UpdateBgLandingPageImage($userID, $landingImage) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_section_two_bg = ? WHERE configuration_id = 1", [(string)$landingImage]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Fourth Landing Page Image*/
    public function iN_UpdateFrntLandingPageImage($userID, $landingImage) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET landing_section_feature_image = ? WHERE configuration_id = 1", [(string)$landingImage]);
            return true;
        } else {
            return false;
        }
    }
	/*Insert New Question Answer*/
    public function iN_InsertNewQuestionAnswer($userID, $newQusetionAnswer, $newQusetion) {
        // Make them into AND conditions
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("INSERT INTO i_landing_qa(qa_title, qa_description, qa_status) VALUES(?, ?, '1')", [(string)$newQusetion, (string)$newQusetionAnswer]);
            return true;
        }
    }
	/*Delete Post*/
    public function iN_DeleteQA($userID, $QAID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckQAExistByID($QAID) == 1) {
            DB::exec("DELETE FROM i_landing_qa WHERE qa_id = ?", [(int)$QAID]);
            return true;
        } else {
            return false;
        }
    }
	/*Get QA Details From ID*/
    public function iN_GetQADetailsFromID($sID) {
        return DB::one("SELECT * FROM i_landing_qa WHERE qa_id = ?", [(int)$sID]);
    }
	/*Update Fourth Landing Page Image*/
    public function iN_UpdateLandingQA($userID, $newQusetionAnswer, $newQusetion, $QAID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_landing_qa SET qa_title = ?, qa_description = ? WHERE qa_id = ?", [(string)$newQusetion,(string)$newQusetionAnswer,(int)$QAID]);
            return true;
        } else {
            return false;
        }
    }
	/*Check Live EXISTs*/
    public function iN_CheckLiveIDExistAndOwner($userID, $liveID) {
        return (bool) DB::col("SELECT 1 FROM i_live WHERE live_id = ? AND live_uid_fk = ? LIMIT 1", [(int)$liveID,(int)$userID]);
    }
	/*Finish Live Streaming*/
    public function iN_FinishLiveStreaming($userID, $liveID) {
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckLiveIDExistAndOwner($userID, $liveID) > 0) {
            DB::exec("DELETE FROM i_live WHERE live_id = ? AND live_uid_fk = ?", [(int)$liveID,(int)$userID]);
            return true;
        }else{
            return false;
        }
    }
	/*Update Subscription Stripe Details*/
    public function iN_UpdateSubCCBILLDetails($userID, $accountNumber, $subAccountNumber, $flexFormID, $saltKey, $ccbillCurrency) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET ccbill_account_number = ?, ccbill_subaccount_number = ?, ccbill_flex_form_id = ?, ccbill_salt_key = ?, ccbill_currency = ? WHERE payment_method_id = 1",
                [(string)$accountNumber,(string)$subAccountNumber,(string)$flexFormID,(string)$saltKey,(string)$ccbillCurrency]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Payments Withdrawal AND Subscription Payments List*/
    public function iN_GetUWithdrawalDetails($userID, $withdrawID , $type) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $sql = "SELECT DISTINCT P.payout_id, P.iuid_fk, P.amount, P.method, P.paid_time, P.payout_time, P.status, P.payment_type, U.iuid, U.i_username, U.i_user_fullname
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_user_payouts P FORCE INDEX(ix_PayoutUser) ON P.iuid_fk = U.iuid AND U.uStatus IN('1','3')
                    WHERE P.payment_type = ? AND P.payout_id = ?";
            return DB::one($sql, [(string)$type,(int)$withdrawID]);
        }
    }
	/*Update DigitalOcean Settings*/
    public function iN_UpdateDigitalOceanDetails($userID, $dOceanRegion, $dOgeanBucket, $dOceanKey, $dOceanSecretKey,  $dOceanStatus) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::begin();
            try {
                DB::exec("UPDATE i_configurations SET s3_status = '0' WHERE configuration_id = 1");
                DB::exec("UPDATE i_configurations SET ocean_region = ?, ocean_space_name = ?, ocean_key = ?, ocean_secret = ?, ocean_status = ? WHERE configuration_id = 1",
                    [(string)$dOceanRegion,(string)$dOgeanBucket,(string)$dOceanKey,(string)$dOceanSecretKey,(string)$dOceanStatus]
                );
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        } else {
			return false;
		}
	}

	/*Update FFMPEG Mod*/
    public function iN_UpdateFFMPEGSendStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET ffmpeg_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Post Create Mod*/
    public function iN_UpdatePostCretaeStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET normal_user_can_post = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update UPLOADED FILES FROM UPLOADS TABLE*/
    public function iN_UpdateUploadedFiles($userID, $tumbnailPath, $pathFileID) {
        DB::exec("UPDATE i_user_uploads SET upload_tumbnail_file_path = ?, uploaded_x_file_path = ? WHERE iuid_fk = ? AND upload_id = ?",
            [(string)$tumbnailPath,(string)$tumbnailPath,(int)$userID,(int)$pathFileID]
        );
        return true;
    }
	/*Remove Youtubelink from Post Text*/
	public function iN_RemoveYoutubelink($postText){
		if($postText){
			$remove = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","",$postText);
		}else{
			$remove = $postText;
		}
		return $remove;
	}
	/*Suggestion Creators*/
    public function iN_SuggestionCreatorsList($uid, $numberShow) {
        $numberShow = (int)$numberShow;
        $sql = "SELECT iu.*
                FROM i_users iu
                LEFT JOIN i_friends ifr ON iu.iuid = ifr.fr_two AND ifr.fr_one = ?
                WHERE iu.uStatus = '3' AND ifr.fr_two IS NULL
                ORDER BY RAND()
                LIMIT $numberShow";
        $rows = DB::all($sql, [(int)$uid]);
        return !empty($rows) ? $rows : null;
    }
	/*Suggestion Creators*/
    public function iN_SuggestionCreatorsListOut($numberShow) {
        $numberShow = (int)$numberShow;
        $sql = "SELECT * FROM i_users WHERE uStatus IN('3') AND certification_status = '2' AND validation_status = '2' AND condition_status = '2' AND fees_status = '2' ORDER BY RAND() LIMIT $numberShow";
        $rows = DB::all($sql);
        return !empty($rows) ? $rows : null;
    }
	/* Check if a country is blocked for the user */
	public function iN_CheckCountryBlocked($userID, $value) {

        if ($this->iN_CheckUserExist($userID) == '1' && !empty($value)) {
            return (bool) DB::col("SELECT 1 FROM i_user_blocked_countries WHERE b_iuid_fk = ? AND b_country = ? LIMIT 1", [(int)$userID,(string)$value]);
        }

		return false;
	}
	/* Insert Country Code in Block List */
    public function iN_InsertCountryInBlockList($userID, $blockingCountryCode) {

        $time = time();

        // Only proceed if user exists and country code is valid
        if ($this->iN_CheckUserExist($userID) == '1' && !empty($blockingCountryCode)) {
            DB::exec("INSERT INTO i_user_blocked_countries (b_iuid_fk, b_country, b_time) VALUES (?,?,?)",
                [(int)$userID,(string)$blockingCountryCode,$time]
            );
            return true;
        }

        return false;
    }
	/* Remove a country from blocked list */
    public function iN_RemoveCountryInBlockList($userID, $blockingCountryCode) {

        if ($this->iN_CheckUserExist($userID) == '1' && !empty($blockingCountryCode)) {
            DB::exec("DELETE FROM i_user_blocked_countries WHERE b_iuid_fk = ? AND b_country = ?", [(int)$userID,(string)$blockingCountryCode]);
            return true;
        }

        return false;
    }
	/*Following List*/
    public function iN_FollowingUsersListPage($userID, $paginationLimit, $page) {
        $userID = (int)$userID; $paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;

        if ($this->iN_CheckUserExist($userID) == 1) {
            $sql = "SELECT DISTINCT F.fr_id, F.fr_one, F.fr_two, F.fr_status,
                           U.iuid, U.i_username, U.i_user_fullname
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_friends F FORCE INDEX(ixFriend)
                      ON F.fr_one = U.iuid AND U.uStatus IN('1','3')
                    WHERE F.fr_one = ? AND F.fr_status = 'flwr'
                    ORDER BY F.fr_id DESC
                    LIMIT $start_from, $paginationLimit";
            $rows = DB::all($sql, [$userID]);
            return !empty($rows) ? $rows : null;
        }
    }

	/*User Total Following User*/
    public function iN_UserTotalFollowingUsers($userID) {
        $userID = (int)$userID;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_friends WHERE fr_one = ? AND fr_status = 'flwr'", [$userID]);
            return $val ? (int)$val : 0;
        }
    }
	/*Following List*/
    public function iN_FollowerUsersListPage($userID, $paginationLimit, $page) {
        $userID = (int)$userID; $paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;

        if ($this->iN_CheckUserExist($userID) == 1) {
            $sql = "SELECT DISTINCT F.fr_id, F.fr_one, F.fr_two, F.fr_status,
                           U.iuid, U.i_username, U.i_user_fullname, U.user_frame
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_friends F FORCE INDEX(ixFriend)
                      ON F.fr_two = U.iuid AND U.uStatus IN('1','3')
                    WHERE F.fr_two = ? AND F.fr_status = 'flwr'
                    ORDER BY F.fr_id DESC
                    LIMIT $start_from, $paginationLimit";
            $rows = DB::all($sql, [$userID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Followers List Profile*/
	public function iN_FollowerUsersListProfilePage($userID, $lastPostID, $showingPost) {
        $userID = (int)$userID; $lastPostID = (int)$lastPostID; $showingPosts = (int)$showingPost;
        $morePost = "";
        if ($lastPostID) {
            $morePost = " AND F.fr_id < '$lastPostID'";
        }
        $data = array();
        if ($this->iN_CheckUserExist($userID) == 1) {
            $sql = "SELECT DISTINCT F.fr_id, F.fr_one, F.fr_two, F.fr_status, U.iuid, U.i_username, U.i_user_fullname, U.user_frame
                    FROM i_users U INNER JOIN i_friends F ON F.fr_two = U.iuid
                    WHERE F.fr_two = ? AND F.fr_status = 'flwr' $morePost
                    ORDER BY F.fr_id DESC
                    LIMIT $showingPost";
            $rows = DB::all($sql, [$userID]);
            return !empty($rows) ? $rows : null;
        }
    }
    /*Following List Profile*/
    public function iN_FollowingUsersListProfilePage($userID, $lastPostID, $showingPost) {
        $userID = (int)$userID; $lastPostID = (int)$lastPostID; $showingPosts = (int)$showingPost;
        $morePost = "";
        if ($lastPostID) {
            $morePost = " AND F.fr_id < '$lastPostID'";
        }
        $data = array();
        if ($this->iN_CheckUserExist($userID) == 1) {
            $sql = "SELECT DISTINCT F.fr_one, F.fr_two, F.fr_status, F.fr_id, U.iuid, U.i_username, U.i_user_fullname, U.user_frame
                    FROM i_users U INNER JOIN i_friends F ON F.fr_one = U.iuid
                    WHERE F.fr_one = ? AND F.fr_status = 'flwr' $morePost
                    ORDER BY F.fr_id DESC
                    LIMIT $showingPost";
            $rows = DB::all($sql, [$userID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Following List Profile*/
    public function iN_SubscribersUsersListProfilePage($userID, $lastPostID, $showingPost) {
        $userID = (int)$userID; $lastPostID = (int)$lastPostID; $showingPosts = (int)$showingPost;
        $morePost = "";
        if ($lastPostID) {
            $morePost = " AND F.fr_id < '$lastPostID'";
        }
        $data = array();
        if ($this->iN_CheckUserExist($userID) == 1) {
            $sql = "SELECT DISTINCT F.fr_one, F.fr_two, F.fr_status, F.fr_id, U.iuid, U.i_username, U.i_user_fullname, U.user_frame
                    FROM i_users U INNER JOIN i_friends F ON F.fr_two = U.iuid
                    WHERE F.fr_two = ? AND F.fr_status = 'subscriber' $morePost
                    ORDER BY F.fr_id DESC
                    LIMIT $showingPost";
            $rows = DB::all($sql, [$userID]);
            return !empty($rows) ? $rows : null;
        }
    }

	/*User Total Following User*/
public function iN_UserTotalFollowerUsers($userID) {
		$userID = (int)$userID;
		if ($this->iN_CheckUserExist($userID) == 1) {
			$val = DB::col("SELECT COUNT(*) FROM i_friends WHERE fr_two = ? AND fr_status = 'flwr'", [$userID]);
			return $val ? (int)$val : 0;
		}
}
	/*All Posts*/
    public function iN_AllTypeQuestionsList($userID, $paginationLimit, $page) {
        $userID = (int)$userID; $paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $rows = DB::all("SELECT * FROM i_contacts WHERE contact_read_status IN('0','1') ORDER BY contact_id DESC LIMIT $start_from, $paginationLimit");
            return !empty($rows) ? $rows : null;
        }
    }
	/*All Reported Posts*/
    public function iN_AllTypeReportedPostList($userID, $paginationLimit, $page) {
        $userID = (int)$userID; $paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $rows = DB::all("SELECT * FROM i_post_reports WHERE report_status IN('0','1') ORDER BY p_report_id DESC LIMIT $start_from, $paginationLimit");
            return !empty($rows) ? $rows : null;
        }
    }
	/*All Reported Comments*/
public function iN_AllTypeReportedCommentList($userID, $paginationLimit, $page) {
		$userID = (int)$userID; $paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $rows = DB::all("SELECT * FROM i_comment_reports WHERE report_status IN('0','1') ORDER BY p_report_id DESC LIMIT $start_from, $paginationLimit");
            return !empty($rows) ? $rows : null;
        }
	}
	/*Check post EXISTs*/
	public function iN_CheckQuestionIDExist($qID) {
        return (bool) DB::col("SELECT 1 FROM i_contacts WHERE contact_id = ? LIMIT 1", [(int)$qID]);
    }
	/*Check post EXISTs*/
	public function iN_CheckReportIDExist($qID) {
        return (bool) DB::col("SELECT 1 FROM i_post_reports WHERE p_report_id = ? LIMIT 1", [(int)$qID]);
    }
	/*Delete question*/
	public function iN_DeleteQuestion($userID, $postID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckQuestionIDExist($postID) == 1) {
            DB::exec("DELETE FROM i_contacts WHERE contact_id = ?", [(int)$postID]);
            return true;
        } else {
            return false;
        }
    }
	/*Get Question Details*/
	public function iN_GetUQuestionDetails($userID, $quID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckQuestionIDExist($quID) == 1) {
            return DB::one("SELECT * FROM i_contacts WHERE contact_id = ?", [(int)$quID]);
        }
    }
	/*Update Question Answer Status*/
	public function iN_UpdateQuestionAnswerStatus($userID, $mod, $questionID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckQuestionIDExist($questionID) == 1) {
            DB::exec("UPDATE i_contacts SET contact_read_status = ? WHERE contact_id = ?", [(string)$mod,(int)$questionID]);
            return true;
        } else {
            return false;
        }
    }
	/*Delete Report*/
	public function iN_DeleteReport($userID, $postID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckReportIDExist($postID) == 1) {
            DB::exec("DELETE FROM i_post_reports WHERE p_report_id = ?", [(int)$postID]);
            return true;
        } else {
            return false;
        }
	}
	/*Update Reported Post Checked Status*/
    public function iN_UpdateReportedPostCheckedStatus($userID, $mod, $rID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckReportIDExist($rID) == 1) {
            DB::exec("UPDATE i_post_reports SET report_status = ? WHERE p_report_id = ?", [(string)$mod,(int)$rID]);
            return true;
        } else {
            return false;
        }
    }
	/*Get Total Unreaded notifications */
    public function iN_GetTotalReportedPost($uid) {
        $uid = (int)$uid;
        if ($this->iN_CheckIsAdmin($uid) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_post_reports WHERE report_status='0'");
            return (int)$val;
        } else {
            return false;
        }
    }
	/*Update Reported Post Checked Status*/
    public function iN_UpdateReportedCommentCheckedStatus($userID, $mod, $rID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_comment_reports SET report_status = ? WHERE p_report_id = ?", [(string)$mod,(int)$rID]);
            return true;
        } else {
            return false;
        }
    }
	/*Get Total Unreaded notifications */
	public function iN_GetTotalReportedComment($uid) {
        $uid = (int)$uid;
        if ($this->iN_CheckIsAdmin($uid) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_comment_reports WHERE report_status='0'");
            return (int)$val;
        } else {
            return false;
        }
    }
	/*Check post EXISTs*/
	public function iN_CheckReportCommentIDExist($qID) {
        return (bool) DB::col("SELECT 1 FROM i_comment_reports WHERE p_report_id = ? LIMIT 1", [(int)$qID]);
    }
	/*Delete Comment Report*/
	public function iN_DeleteCommentReport($userID, $postID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckReportCommentIDExist($postID) == 1) {
            DB::exec("DELETE FROM i_comment_reports WHERE p_report_id = ?", [(int)$postID]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Post Create Mod*/
	public function iN_UpdateBlockCountriesStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET user_can_block_country = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Autp approve post Status*/
	public function iN_UpdateAutoApprovePostStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET auto_approve_post = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*User Total Point Purchased*/
	public function iN_UserTotalPointPurchase($userID) {
        $userID = (int)$userID;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_payments WHERE payer_iuid_fk = ? AND payment_type = 'point' AND credit_plan_id IS NOT NULL", [$userID]);
            return $val ? (int)$val : 0;
        }
    }
	/*Payments Point History List*/
    public function iN_YourPointPaymentsHistoryList($userID, $paginationLimit, $page) {
        $userID = (int)$userID; $paginationLimit = (int)$paginationLimit; $page = (int)$page; $start_from = ($page - 1) * $paginationLimit;

        if ($this->iN_CheckUserExist($userID) == 1) {
            $fiveHoursAgo = time() - (5 * 60 * 60);
            DB::exec("DELETE FROM i_user_payments WHERE payment_type = 'point' AND payment_status = 'pending' AND credit_plan_id IS NOT NULL AND payment_time < ?", [$fiveHoursAgo]);

            $sql = "SELECT DISTINCT P.payment_id, P.payer_iuid_fk, P.order_key, P.payment_type,
                                P.payment_option, P.payment_time, P.payment_status, P.credit_plan_id
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_user_payments P FORCE INDEX(ixPayment)
                        ON P.payer_iuid_fk = U.iuid AND U.uStatus IN('1','3')
                    WHERE P.payer_iuid_fk = ?
                      AND P.payment_type = 'point'
                      AND P.credit_plan_id IS NOT NULL
                    ORDER BY P.payment_time DESC
                    LIMIT $start_from, $paginationLimit";
            $rows = DB::all($sql, [(int)$userID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*All Live Streamings List Widget*/
	public function iN_LiveStreamingListWidget($showingPost){
		$tree_minutes_ago = time() - (10 * 1);
        $datetime = date("Y-m-d H:i:s", $tree_minutes_ago);
        $showingPost = (int)$showingPost;
        $sql = "SELECT DISTINCT L.live_id, L.live_name, L.live_uid_fk, L.finish_time, L.live_credit, L.live_type, U.iuid, U.i_username, U.i_user_fullname
                FROM i_live L FORCE INDEX(ix_Live)
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON L.live_uid_fk = U.iuid AND U.uStatus IN('1','3')
                WHERE L.finish_time >= ? ORDER BY L.live_id DESC LIMIT $showingPost";
        $rows = DB::all($sql, [$tree_minutes_ago]);
        return !empty($rows) ? $rows : null;
	}
	/*All Live Streamings List*/
	public function iN_LiveStreaminsListAllType($lastPostID, $showingPost) {
		$tree_minutes_ago = time() - (10 * 1);
		$datetime = date("Y-m-d H:i:s", $tree_minutes_ago);
		$morePost = "";
		if ($lastPostID) {
			$morePost = " AND L.live_id <'" . $lastPostID . "' ";
		}
        $showingPost = (int)$showingPost;
        $sql = "SELECT DISTINCT L.live_id, L.live_name, L.live_uid_fk, L.finish_time, L.live_credit, L.live_type, U.iuid, U.i_username, U.i_user_fullname
                FROM i_live L FORCE INDEX(ix_Live)
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON L.live_uid_fk = U.iuid AND U.uStatus IN('1','3')
                WHERE L.finish_time >= ? $morePost ORDER BY L.live_id DESC LIMIT $showingPost";
        $rows = DB::all($sql, [$tree_minutes_ago]);
        return !empty($rows) ? $rows : null;
	}
	/*Insert User Subscription Using Point*/
public function iN_InsertUserSubscriptionWithPoint($userID, $subscribedUserID, $planType, $subscriberName, $planAmount, $adminEarning, $userNetEarning, $planCurrency, $planinterval, $planIntervalCount, $subscriberEmail, $plancreated, $current_period_start, $current_period_end, $planStatus,$UpdateCurrentPoint) {
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($subscribedUserID) == 1) {
			try {
				DB::begin();
				DB::exec("INSERT INTO i_user_subscriptions(iuid_fk, subscribed_iuid_fk, subscriber_name, payment_method, plan_amount, admin_earning, user_net_earning, plan_amount_currency, plan_interval, plan_interval_count, payer_email, created, plan_period_start, plan_period_end, status)
					VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
					[(int)$userID,(int)$subscribedUserID,(string)$subscriberName,(string)$planType,(string)$planAmount,(string)$adminEarning,(string)$userNetEarning,(string)$planCurrency,(string)$planinterval,(string)$planIntervalCount,(string)$subscriberEmail,(string)$plancreated,(string)$current_period_start,(string)$current_period_end,(string)$planStatus]
				);
				$time = time();
				$exists = (bool) DB::col("SELECT 1 FROM i_friends WHERE fr_one = ? AND fr_two = ? AND (fr_status = 'flwr' OR fr_status = 'subscriber') LIMIT 1", [(int)$userID,(int)$subscribedUserID]);
				if ($exists) {
					DB::exec("UPDATE i_friends SET fr_status = 'subscriber', fr_time = ? WHERE fr_one = ? AND fr_two = ?", [$time, (int)$userID,(int)$subscribedUserID]);
				} else {
					DB::exec("INSERT INTO i_friends(fr_one, fr_two, fr_status, fr_time) VALUES(?, ?, 'subscriber', ?)", [(int)$userID,(int)$subscribedUserID,$time]);
				}
				DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [(string)$userNetEarning,(int)$subscribedUserID]);
				DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [(string)$planAmount,(int)$userID]);
				DB::exec("UPDATE i_user_subscriptions SET plan_period_start = ?, plan_period_end = ? WHERE iuid_fk = ? AND subscribed_iuid_fk = ?",
					[(string)$current_period_end,(string)$current_period_start,(int)$userID,(int)$subscribedUserID]
				);
				DB::commit();
				return true;
			} catch (Throwable $e) { DB::rollBack(); return false; }
		}
}
	/*Update Users Wallets*/
public function iN_UpdateUsersWallets($userID, $tiSendingUserID, $tipAmount, $netUserEarning,$adminFee,$adminEarning,$userNetEarning){
		if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($tiSendingUserID) == 1) {
			try { DB::begin();
				$time = time();
				DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [(string)$tipAmount,(int)$userID]);
				DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [(string)$userNetEarning,(int)$tiSendingUserID]);
				DB::exec("INSERT INTO i_user_payments(payer_iuid_fk, payed_iuid_fk, payment_type, payment_time, payment_status, amount, user_earning, admin_earning, fee)
					VALUES(?, ?, 'tips', ?, 'ok', ?, ?, ?, ?)",
					[(int)$userID,(int)$tiSendingUserID,$time,(string)$netUserEarning,(string)$userNetEarning,(string)$adminEarning,(string)$adminFee]
				);
				DB::commit();
				return true;
			} catch (Throwable $e) { DB::rollBack(); return false; }
		}
}
	/*Update CoinPayment Status*/
public function iN_UpdateCoinPaymentStatus($userID, $mode) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("UPDATE i_payment_methods SET coinpayments_status = ? WHERE payment_method_id = 1", [(string)$mode]);
			return true;
		} else {
			return false;
		}
}
	/*Update Stripe Details*/
public function iN_UpdateCoinPaymentDetails($userID, $cpPrivateKey, $cpPublicKey, $cpMerchandID, $cpIPNSecret, $cpDebugEmail, $cpCurrency) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("UPDATE i_payment_methods SET coinpayments_private_key = ?, coinpayments_public_key = ?, coinpayments_merchand_id = ?, coinpayments_ipn_secret = ?, coinpayments_debug_email = ?, cp_cryptocurrencies = ? WHERE payment_method_id = 1",
				[(string)$cpPrivateKey,(string)$cpPublicKey,(string)$cpMerchandID,(string)$cpIPNSecret,(string)$cpDebugEmail,(string)$cpCurrency]
			);
			return true;
		} else {
			return false;
		}
}
	/*All Live Streamings List*/
public function iN_LiveStreaminsListAllTypeSuggested($lastPostID,$userID, $showingPost) {
		$tree_minutes_ago = time() - (10 * 1);
		$datetime = date("Y-m-d H:i:s", $tree_minutes_ago);
		$morePost = "";
		if ($lastPostID) {
			$morePost = " AND L.live_id <'" . $lastPostID . "' ";
		}
		$showingPost = (int)$showingPost;
		$sql = "SELECT DISTINCT L.live_id, L.live_name, L.live_uid_fk, L.finish_time, L.live_credit, L.live_type, U.iuid, U.i_username, U.i_user_fullname
		FROM i_live L FORCE INDEX(ix_Live)
		INNER JOIN i_users U FORCE INDEX(ixForceUser) ON L.live_uid_fk = U.iuid AND U.uStatus IN('1','3')
		WHERE L.finish_time >= ? AND L.live_uid_fk != ? $morePost ORDER BY L.live_id DESC LIMIT $showingPost";
		$rows = DB::all($sql, [$tree_minutes_ago, (int)$userID]);
		return !empty($rows) ? $rows : null;
}
	/*Premium Live Gif Plan List*/
public function iN_LiveGifPlansListFromAdmin() {
		$rows = DB::all("SELECT * FROM i_live_gift_point");
		return !empty($rows) ? $rows : null;
}
	/*Insert New Advertisement*/
public function iN_InsertNewGiftCard($userID, $giftImage, $giftName, $giftPoint, $giftAmount,$gifAnimationImage) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("INSERT INTO i_live_gift_point(gift_name,gift_point,gift_image,gift_money_equal,gift_money_animation_image) VALUES(?,?,?,?,?)",
				[(string)$giftName,(string)$giftPoint,(string)$giftImage,(string)$giftAmount,(string)$gifAnimationImage]
			);
			return true;
		}
}
	/*Insert New Frame Plan*/
public function iN_InsertNewFrameCard($userID, $giftImage, $giftPoint) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("INSERT INTO i_frames(f_file,f_price) VALUES(?,?)", [(string)$giftImage,(string)$giftPoint]);
			return true;
		}
}
	/*Check Premium Plan Exist*/
public function GetLivePlanDetails($planID) {
		if ($this->CheckLivePlanExist($planID) == '1') {
			return DB::one("SELECT * FROM i_live_gift_point WHERE gift_id = ?", [(int)$planID]);
		} else {return false;}
}
	/*Check Premium Plan Exist*/
public function GetFramePlanDetails($planID) {
		if ($this->CheckFramePlanExist($planID) == '1') {
			return DB::one("SELECT * FROM i_frames WHERE f_id = ?", [(int)$planID]);
		} else {return false;}
}
	/*Update Live Point Plan*/
public function iN_UpdateLivePlanFromID($userID, $giftName, $gifImage, $giftAnimation, $giftPoint, $giftAmount, $giftID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckLivePlanExist($giftID)) {
			DB::exec("UPDATE i_live_gift_point SET gift_name = ?, gift_image = ?, gift_point = ?, gift_money_equal = ?, gift_money_animation_image = ? WHERE gift_id = ?",
				[(string)$giftName,(string)$gifImage,(string)$giftPoint,(string)$giftAmount,(string)$giftAnimation,(int)$giftID]
			);
			return true;
		} else {
			return false;
		}
}
	/*Update Plan Status*/
public function iN_UpdateLivePlanStatus($userID, $mod, $planID) {
		$planID = (int)$planID;
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckLivePlanExist($planID)) {
			DB::exec("UPDATE i_live_gift_point SET gift_status = ? WHERE gift_id = ?", [(string)$mod,(int)$planID]);
			return true;
		} else {
			return false;
		}
}
	/*Delete Plan*/
public function iN_DeleteLivePlanFromData($userID, $planID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckLivePlanExist($planID) == 1) {
			DB::exec("DELETE FROM i_live_gift_point WHERE gift_id = ?", [(int)$planID]);
			return true;
		} else {
			return false;
		}
}
	/*Delete Plan*/
public function iN_DeleteFramePlanFromData($userID, $planID) {
		if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckFramePlanExist($planID) == 1) {
			DB::exec("DELETE FROM i_frames WHERE f_id = ?", [(int)$planID]);
			return true;
		} else {
			return false;
		}
}
	/*Premium Plan List*/
public function iN_LiveGiftSendList() {
		$rows = DB::all("SELECT * FROM i_live_gift_point WHERE gift_status = '1'");
		return !empty($rows) ? $rows : null;
}
	/*Update Users Wallets*/
public function iN_UpdateUsersWalletsForLiveGift($userID,$cLiveID, $tiSendingUserID, $giftDataID,$liveWantedCoin, $adminEarning, $netUserEarning,$liveWantedMoney){
        if ($this->iN_CheckUserExist($userID) == 1 && $this->CheckLivePlanExist($giftDataID) == 1 && $this->iN_CheckUserExist($tiSendingUserID) == 1) {
            try {
                DB::begin();
                $time = time();
                DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [(string)$liveWantedCoin,(int)$userID]);
                DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [(string)$netUserEarning,(int)$tiSendingUserID]);
                DB::exec("INSERT INTO i_user_payments(payer_iuid_fk, payed_iuid_fk, payment_type, payment_time, payment_status, amount, admin_earning, user_earning) VALUES(?, ?, 'live_gift', ?, 'ok', ?, ?, ?)",
                         [(int)$userID,(int)$tiSendingUserID,$time,(string)$liveWantedMoney,(string)$adminEarning,(string)$netUserEarning]);
                DB::exec("INSERT INTO i_live_chat(cm_live_id, cm_iuid_fk, cm_gift_type, cm_time) VALUES(?,?,?,?)", [(int)$cLiveID,(int)$userID,(int)$giftDataID,$time]);
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        }
	}
	/*Live Chat List*/
    public function iN_LiveChatMessages($liveID, $userID){
        if($this->iN_CheckLiveIDExist($liveID) == 1 && $this->iN_CheckUserExist($userID) == 1){
            $rows = DB::all("SELECT * FROM i_live_chat WHERE cm_live_id = ?", [(int)$liveID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Get New Live Messages*/
    public function iN_GetNewLiveMessage($liveID, $lastLiveMessageID) {
        if ($this->iN_CheckLiveIDExist($liveID) == 1) {
            $params = [(int)$liveID];
            $cond = '';
            if (!empty($lastLiveMessageID) && is_numeric($lastLiveMessageID)) {
                $cond = ' AND cm_id > ?';
                $params[] = (int)$lastLiveMessageID;
            }
            $sql = "SELECT * FROM i_live_chat WHERE cm_live_id = ? $cond ORDER BY cm_id";
            return DB::all($sql, $params);
        }
        return [];
    }
	/*Insert and Get new Live Message*/
public function iN_InsertLiveMessage($liveID,$liveMessage, $userID) {
		if($this->iN_CheckLiveIDExist($liveID) == 1 && $this->iN_CheckUserExist($userID) == 1){
			$time = time();
			DB::exec("INSERT INTO i_live_chat(cm_live_id, cm_iuid_fk, cm_message, cm_time) VALUES(?,?,?,?)", [(int)$liveID,(int)$userID,(string)$liveMessage,$time]);
			$sql = "SELECT M.cm_id,M.cm_live_id, M.cm_iuid_fk, M.cm_message, M.cm_time,M.cm_gift_type, U.iuid,U.i_username,U.i_user_fullname,U.user_avatar,U.user_gender,U.last_login_time,U.user_verified_status
			FROM i_live_chat M FORCE INDEX(LiveChatIndex)
				INNER JOIN i_users U FORCE INDEX(ixForceUser)
				ON M.cm_iuid_fk = U.iuid
			WHERE M.cm_live_id = ? AND M.cm_iuid_fk = ? ORDER BY M.cm_id DESC LIMIT 1";
			return DB::one($sql, [(int)$liveID,(int)$userID]);
		} else{
			return false;
		}
}
	/*All Live Streamings List*/
public function iN_LiveStreaminsListAllTypeBottom($lastPostID, $showingPost,$userID) {
		$tree_minutes_ago = time() - (10 * 1);
		$datetime = date("Y-m-d H:i:s", $tree_minutes_ago);
		$morePost = "";
		if ($lastPostID) {
			$morePost = " AND L.live_id <'" . $lastPostID . "' ";
		}
		$showingPost = (int)$showingPost;
		$sql = "SELECT DISTINCT L.live_id, L.live_name, L.live_uid_fk, L.finish_time, L.live_credit, L.live_type, U.iuid, U.i_username, U.i_user_fullname
		FROM i_live L FORCE INDEX(ix_Live)
		INNER JOIN i_users U FORCE INDEX(ixForceUser) ON L.live_uid_fk = U.iuid AND L.live_uid_fk != ? AND U.uStatus IN('1','3')
		WHERE L.finish_time >= ? $morePost ORDER BY L.live_id DESC LIMIT $showingPost";
		$rows = DB::all($sql, [(int)$userID, $tree_minutes_ago]);
		return !empty($rows) ? $rows : null;
	}
    public function iN_OneSignalDeviceKey($userID, $userDeviceOneSignalKey) {
        if($this->iN_CheckUserExist($userID) == 1){
            DB::exec("UPDATE i_users SET device_key = ? WHERE iuid = ?", [(string)$userDeviceOneSignalKey,(int)$userID]);
            return true;
        }

    }
    public function iN_OneSignalDeviceKeyRemove($userID) {
        DB::exec("UPDATE i_users SET device_key = NULL WHERE iuid = ?", [(int)$userID]);
        return true;
    }
	public function iN_OneSignalPushNotificationSend($msg_body, $msg_title, $url, $device_id, $oneSignalApi, $oneSignalRestApi) {
		$content = array(
			"en" => $msg_body,
		);
		$heading = array(
			"en" => $msg_title,
		);
		$include_player_id = array(
			$device_id,
		);

		$msg_img = '';
		$fields = array(
			'app_id' => $oneSignalApi,
			'contents' => $content,
			'headings' => $heading,
			'data' => array("foo" => "bar"),
			'small_icon' => "ic_launcher",
			'large_icon' => "ic_launcher",
			'image' => $msg_img,
			'include_player_ids' => $include_player_id,
			'url' => $url,
		);
		$fields = json_encode($fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
			'Authorization: Basic ' . $oneSignalRestApi . ''));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}
	/*Check Affilate ID*/
    public function iN_CheckAffilateID($affilateID){
        return (bool) DB::col("SELECT 1 FROM i_configuration_affilate WHERE i_af_id = ? LIMIT 1", [(int)$affilateID]);
    }
	/*Get Register Affilate Details*/
    public function iN_GetRegisterAffilateData($type, $affilateID){
      if(!empty($type) && $this->iN_CheckAffilateID($affilateID) == '1'){
         return DB::one("SELECT * FROM i_configuration_affilate WHERE i_af_id = ? AND i_af_type = ? AND i_af_status = 'yes'", [(int)$affilateID, (string)$type]);
      }
    }
	/*Update Affilate System Mod*/
    public function iN_UpdateAffilateSystemStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET affilate_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Weekly Sub Mod*/
    public function iN_UpdateWeeklySubStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET sub_weekly_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Monthly Sub Mod*/
    public function iN_UpdateMonthlySubStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET sub_mountly_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Yearly Sub Mod*/
    public function iN_UpdateYearlySubStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET sub_yearly_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Watermark Mod*/
    public function iN_UpdateWatermarkStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET watermark_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Afflate Mod*/
    public function iN_UpdateAffiliateStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET affilate_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Site Business Informations*/
    public function iN_UpdateAffilateInfos($userID, $minimumPointTransferAmount, $affilateEarnAmount) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET minimum_point_transfer_request = ?, affilate_amount = ? WHERE configuration_id = 1", [(string)$minimumPointTransferAmount,(string)$affilateEarnAmount]);
            return true;
        } else {
            return false;
        }
    }
	/*Get user Earn Point List*/
    public function iN_GetUserEarnPointData($userID){
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $rows = DB::all("SELECT * FROM i_configuration_affilate");
            return !empty($rows) ? $rows : null;
        }
    }
	/*Update EPD*/
    public function iN_EPDUpdate($userID, $maxPoint, $epdRegisterStatus, $epdCommentStatus, $epdNewPostStatus, $epdCommetLikeStatus, $epdPostLikeStatus, $epdRegisterAmount, $epdCommendAmount, $epdCommentLikeAmount, $epdNewPostAmount, $epdPostLikeAmount) {
        try {
            DB::begin();
            DB::exec("UPDATE i_configuration_affilate SET i_af_amount = ? WHERE i_af_id = '1'", [(string)$epdRegisterAmount]);
            DB::exec("UPDATE i_configuration_affilate SET i_af_status = ? WHERE i_af_id = '1'", [(string)$epdRegisterStatus]);
            DB::exec("UPDATE i_configuration_affilate SET i_af_amount = ? WHERE i_af_id = '2'", [(string)$epdCommendAmount]);
            DB::exec("UPDATE i_configuration_affilate SET i_af_status = ? WHERE i_af_id = '2'", [(string)$epdCommentStatus]);
            DB::exec("UPDATE i_configuration_affilate SET i_af_amount = ? WHERE i_af_id = '3'", [(string)$epdPostLikeAmount]);
            DB::exec("UPDATE i_configuration_affilate SET i_af_status = ? WHERE i_af_id = '3'", [(string)$epdPostLikeStatus]);
            DB::exec("UPDATE i_configuration_affilate SET i_af_amount = ? WHERE i_af_id = '4'", [(string)$epdCommentLikeAmount]);
            DB::exec("UPDATE i_configuration_affilate SET i_af_status = ? WHERE i_af_id = '4'", [(string)$epdCommetLikeStatus]);
            DB::exec("UPDATE i_configuration_affilate SET i_af_amount = ? WHERE i_af_id = '5'", [(string)$epdNewPostAmount]);
            DB::exec("UPDATE i_configuration_affilate SET i_af_status = ? WHERE i_af_id = '5'", [(string)$epdNewPostStatus]);
            DB::exec("UPDATE i_configurations SET max_point_in_a_day = ? WHERE configuration_id = 1", [(string)$maxPoint]);
            DB::commit();
            return true;
        } catch (Throwable $e) { DB::rollBack(); return false; }
    }
	/*Check post EXISTs For Point*/
    public function iN_CheckPostIDExistForPointComment($postID, $userID) {
        $exists = (bool) DB::col(
            "SELECT 1 FROM i_user_point_earnings WHERE poninted_post_id = ? AND pointed_type = 'comment' LIMIT 1",
            [(int)$postID]
        );
        return $exists ? true : false;
    }
	/*Check post EXISTs For Point*/
    public function iN_CheckPostIDExistForPointCommentLike($postID, $userID) {
        $exists = (bool) DB::col(
            "SELECT 1 FROM i_user_point_earnings WHERE poninted_post_id = ? AND pointed_type = 'comment_like' LIMIT 1",
            [(int)$postID]
        );
        return $exists ? true : false;
    }
	/*Insert New Post Point*/
    public function iN_InsertNewPoint($userID,$postID,$pointAmount){
        $time = time();
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1){
            DB::exec(
                "INSERT INTO i_user_point_earnings (poninted_post_id, poninted_user_id, pointed_time, pointed_type, calculated_point, point) VALUES (?,?,?,?,?,?)",
                [(int)$postID, (int)$userID, $time, 'new_post', '0', (string)$pointAmount]
            );
        }
    }
	/*Insert New Post Point*/
    public function iN_InsertNewCommentPoint($userID,$postID,$pointAmount){
        $time = time();
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1 && $this->iN_CheckPostIDExistForPointComment($postID, $userID) == false){
            DB::exec(
                "INSERT INTO i_user_point_earnings (poninted_post_id, poninted_user_id, pointed_time, pointed_type, calculated_point, point) VALUES (?,?,?,?,?,?)",
                [(int)$postID, (int)$userID, $time, 'comment', '0', (string)$pointAmount]
            );
        }
    }
	/*Insert New Post Like Point*/
    public function iN_InsertNewPostLikePoint($userID,$postID,$pointAmount){
        $time = time();
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1 && $this->iN_CheckPostIDExistForPointComment($postID, $userID) == false){
            DB::exec(
                "INSERT INTO i_user_point_earnings (poninted_post_id, poninted_user_id, pointed_time, pointed_type, calculated_point, point) VALUES (?,?,?,?,?,?)",
                [(int)$postID, (int)$userID, $time, 'post_like', '0', (string)$pointAmount]
            );
        }
    }
	/*Insert New Post Comment Like Point*/
    public function iN_InsertNewPostCommentLikePoint($userID,$postID,$pointAmount){
        $time = time();
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckPostIDExist($postID) == 1 && $this->iN_CheckPostIDExistForPointCommentLike($postID, $userID) == false){
            DB::exec(
                "INSERT INTO i_user_point_earnings (poninted_post_id, poninted_user_id, pointed_time, pointed_type, calculated_point, point) VALUES (?,?,?,?,?,?)",
                [(int)$postID, (int)$userID, $time, 'comment_like', '0', (string)$pointAmount]
            );
        }
    }
	/*Remove Point if Earned Before*/
    public function iN_RemovePointIfExist($userID, $postID, $pointAmount){
        if($this->iN_CheckUserExist($userID) == 1){
            $exists = (bool) DB::col("SELECT 1 FROM i_user_point_earnings WHERE poninted_post_id = ? AND poninted_user_id = ? AND pointed_type = 'new_post' LIMIT 1", [(int)$postID, (int)$userID]);
            if($exists){
                DB::exec("DELETE FROM i_user_point_earnings WHERE poninted_post_id = ? AND poninted_user_id = ? AND pointed_type = 'new_post'", [(int)$postID, (int)$userID]);
            }
        }
    }
	/*Remove Point if Earned Before*/
    public function iN_RemovePointCommentIfExist($userID, $postID, $pointAmount){
        if($this->iN_CheckUserExist($userID) == 1){
            $exists = (bool) DB::col("SELECT 1 FROM i_user_point_earnings WHERE poninted_post_id = ? AND poninted_user_id = ? AND pointed_type = 'comment' LIMIT 1", [(int)$postID, (int)$userID]);
            if($exists){
                DB::exec("DELETE FROM i_user_point_earnings WHERE poninted_post_id = ? AND poninted_user_id = ? AND pointed_type = 'comment'", [(int)$postID, (int)$userID]);
            }
        }
    }
	/*Remove Point if Earned Before*/
    public function iN_RemovePointPostLikeIfExist($userID, $postID, $pointAmount){
        if($this->iN_CheckUserExist($userID) == 1){
            $exists = (bool) DB::col("SELECT 1 FROM i_user_point_earnings WHERE poninted_post_id = ? AND poninted_user_id = ? AND pointed_type = 'post_like' LIMIT 1", [(int)$postID, (int)$userID]);
            if($exists){
                DB::exec("DELETE FROM i_user_point_earnings WHERE poninted_post_id = ? AND poninted_user_id = ? AND pointed_type = 'post_like'", [(int)$postID, (int)$userID]);
            }
        }
    }
	/*Remove Point if Earned Before*/
    public function iN_RemovePointPostCommentLikeIfExist($userID, $postID, $pointAmount){
        if($this->iN_CheckUserExist($userID) == 1){
            $exists = (bool) DB::col("SELECT 1 FROM i_user_point_earnings WHERE poninted_post_id = ? AND poninted_user_id = ? AND pointed_type = 'comment_like' LIMIT 1", [(int)$postID, (int)$userID]);
            if($exists){
                DB::exec("DELETE FROM i_user_point_earnings WHERE poninted_post_id = ? AND poninted_user_id = ? AND pointed_type = 'comment_like'", [(int)$postID, (int)$userID]);
            }
        }
    }
	/*Check Post Owner */
    public function iN_CheckPostOwner($userID, $postID){
        return (bool) DB::col("SELECT 1 FROM i_posts WHERE post_owner_id = ? AND post_id = ? LIMIT 1", [(int)$userID, (int)$postID]);
    }
	/*Check Post Owner */
    public function iN_CheckCommentOwner($userID, $postID){
        return (bool) DB::col("SELECT 1 FROM i_post_comments WHERE comment_uid_fk = ? AND comment_post_id_fk = ? LIMIT 1", [(int)$userID, (int)$postID]);
    }
	/*Get user Earn Point List*/
    public function iN_GetUserEarnPointList($userID){
        if ($this->iN_CheckUserExist($userID) == 1) {
            $rows = DB::all("SELECT * FROM i_configuration_affilate WHERE ica_type = 'po' AND i_af_status = 'yes'");
            return !empty($rows) ? $rows : null;
        }
    }
	/*Today Earned comment point*/
    public function iN_TodayEarnedPoint($userID, $type){
        if ($this->iN_CheckUserExist($userID) == 1) {
            $row = DB::one(
                "SELECT point FROM i_user_point_earnings WHERE DATE(NOW()) = DATE(FROM_UNIXTIME(pointed_time)) AND poninted_user_id = ? AND pointed_type = ? AND calculated_point = '0' LIMIT 1",
                [(int)$userID, (string)$type]
            );
            return isset($row['point']) ? $row['point'] : '0';
        }
    }
	/*Current Month Earn Calculate*/
    public function iN_CalculateTotalPointTypeEarningAll($userID, $type) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col(
                "SELECT SUM(point) AS calculate FROM i_user_point_earnings WHERE poninted_user_id = ? AND pointed_type = ? AND calculated_point = '0'",
                [(int)$userID, (string)$type]
            );
            return $val !== false ? $val : '0';
        } else {
            return false;
        }
    }
	/*Update Watermark Link Mod*/
    public function iN_UpdateLinkWatermarkStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET watermark_text_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Show FullName Mod*/
    public function iN_UpdateShowFullNameStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET use_fullname_or_username = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
    public function iN_InsertMentionedUsersForPost($userID, $postDetails, $postID, $dataUsername,$userPostOwnerID) {
        $mention_regex = '/@([A-Za-z0-9_]+)/i';
        $pregMatch = preg_match_all($mention_regex, $postDetails, $matches);
        if ($pregMatch) {
            $mentioned = [];
            foreach ($matches[1] as $match) {
                if ($match !== $dataUsername) {
                    $mentioned[] = $match;
                }
            }
            $mentioned = array_values(array_unique(array_filter($mentioned)));
            if (!empty($mentioned)) {
                $mentionTime = time();
                // Build placeholders for IN clause
                $inPlaceholders = implode(',', array_fill(0, count($mentioned), '?'));
                $params = $mentioned;

                // Insert into mentions
                $sqlMent = "INSERT INTO i_mentions (m_uid_fk, m_type, m_post_id_fk, m_user_owner, m_status, mention_type, m_time)
                            SELECT iuid, NULL, ?, ?, '1', 'post', ? FROM i_users WHERE i_username IN ($inPlaceholders)";
                DB::exec($sqlMent, array_merge([(int)$postID, (int)$userID, $mentionTime], $params));

                // Insert notifications
                $sqlNotif = "INSERT INTO i_user_notifications (not_post_id, not_not_type, not_time, not_own_iuid, not_iuid)
                             SELECT ?, 'umentioned', ?, iuid, ? FROM i_users WHERE i_username IN ($inPlaceholders)";
                DB::exec($sqlNotif, array_merge([(int)$postID, $mentionTime, (int)$userID], $params));

                // Mark notification as unread for mentioned users
                $sqlUpd = "UPDATE i_users SET notification_read_status = '1' WHERE i_username IN ($inPlaceholders)";
                DB::exec($sqlUpd, $params);
            }
        }
    }
	/*Get HashTags*/
    public function iN_GetHashTagsSearchResult($hashTag, $showingPosts) {
        $hashTag = strip_tags(trim((string)$hashTag));
        $hashtags_list = array_filter(array_map('trim', explode(',', $hashTag)));
        if (empty($hashtags_list)) { return null; }

        $conds = [];
        $params = [];
        foreach (array_unique($hashtags_list) as $ht) {
            $conds[] = 'FIND_IN_SET(LOWER(?), LOWER(hashtags))';
            $params[] = mb_strtolower($ht, 'UTF-8');
        }
        $where = implode(' AND ', $conds);
        $limit = (int)$showingPosts;
        $sql = "SELECT * FROM i_posts WHERE ($where) ORDER BY post_id DESC LIMIT $limit";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
    public function iN_GenerateFakeUsers($userID, $fakeUserEmail, $fakeUserUsername, $fakeUserFullName, $fakeUserGender, $fakeUserPassword, $fakeUserBirthDay, $fakeUserRegisterTime, $fakeUserLatitude, $fakeUserLongitude,$random_countries,$fakeUserCategorie) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec(
                "INSERT INTO i_users (i_user_email, i_username, i_user_fullname, user_gender, i_password, birthday, registered, last_login_time, lat, lon, fake_user_status, email_verify_status, countryCode, profile_category)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [
                    (string)$fakeUserEmail, (string)$fakeUserUsername, (string)$fakeUserFullName, (string)$fakeUserGender,
                    (string)$fakeUserPassword, (string)$fakeUserBirthDay, (string)$fakeUserRegisterTime, (string)$fakeUserRegisterTime,
                    (string)$fakeUserLatitude, (string)$fakeUserLongitude, '1', 'yes', (string)$random_countries, (string)$fakeUserCategorie
                ]
            );

            $row = DB::one("SELECT iuid FROM i_users WHERE i_username = ? LIMIT 1", [(string)$fakeUserUsername]);
            if ($row && isset($row['iuid'])) {
                $GetUserID = (int)$row['iuid'];
                $time = time();
                DB::exec("INSERT INTO i_friends (fr_one, fr_two, fr_time, fr_status) VALUES (?,?,?, 'me')", [$GetUserID, $GetUserID, $time]);
            }
            return true;
        } else {return false;}
    }
	/*Update User Can Earn Point Mod*/
    public function iN_UpdateUserCanEarnPointStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET earn_point_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Become a Creator Type Status*/
    public function iN_UpdateBecomeACreatorTypeStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET be_a_creator_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
    public function iN_CheckUserAffilatedBefore($ip,$user){
        if ($this->iN_CheckUserExist($user) == 1) {
            $exists = (bool) DB::col("SELECT 1 FROM i_refUsers WHERE ref_owner_user_id = ? AND ip = ? LIMIT 1", [(int)$user, (string)$ip]);
            return $exists ? false : true;
        }else{
            return false;
        }
    }
	public function iN_CheckCountFile($postFileIDs){
		$trimValue = rtrim($postFileIDs, ',');
		$explodeFiles = explode(',', $trimValue);
		$explodeFiles = array_unique($explodeFiles);
		$i = 0;
		foreach ($explodeFiles as $explodeFile) {
			$theFileID = $this->iN_GetUploadedFileDetails($explodeFile);
			$uploadedFileIDExt = isset($theFileID['uploaded_file_ext']) ? $theFileID['uploaded_file_ext'] : NULL;
			if($uploadedFileIDExt != 'mp3'){
                $i++;
			}
		}
		return isset($i) ? $i : NULL;
	}

	/*GET UPLOADED FILE DATA*/
    public function iN_GetUploadedMp3FileDetails($imageID) {
        if ($imageID) {
            return DB::one("SELECT * FROM i_user_uploads WHERE upload_id = ? AND uploaded_file_ext = 'mp3' LIMIT 1", [(int)$imageID]);
        } else {
            return false;
        }
    }
public function iN_SearchMention($userID, $searchmUser) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $like = '%' . $searchmUser . '%';
            $rows = DB::all("SELECT * FROM i_users WHERE (i_username LIKE ? OR i_user_fullname LIKE ?) ORDER BY iuid LIMIT 5", [$like, $like]);
            return !empty($rows) ? $rows : null;
        }
}
	/*INSERT UPLOADED FILES FROM UPLOADS TABLE*/
public function iN_insertUploadedSotieFiles($uid, $filePath, $tumbnailPath, $fileXPath, $ext) {
        $uploadTime = time();
        $userIP = $_SERVER['REMOTE_ADDR'];
        DB::exec("INSERT INTO i_user_stories (uid_fk, uploaded_file_path, upload_tumbnail_file_path, uploaded_x_file_path, uploaded_file_ext, created) VALUES (?,?,?,?,?,?)",
            [(int)$uid, (string)$filePath, (string)$tumbnailPath, (string)$fileXPath, (string)$ext, $uploadTime]
        );
        return (int) DB::lastId();
}
	/*GET UPLOADED FILE IDs*/
public function iN_GetUploadedStoriesFilesIDs($uid, $imageName) {
        if ($imageName) {
            return DB::one("SELECT * FROM i_user_stories WHERE uid_fk = ? ORDER BY s_id DESC LIMIT 1", [(int)$uid]);
        } else {
            return false;
        }
}
	/*Check Storie ID Exist*/
public function iN_CheckStorieIDExist($userID, $sID){
      if ($this->iN_CheckUserExist($userID) == 1) {
        return (bool) DB::col("SELECT 1 FROM i_user_stories WHERE uid_fk = ? AND s_id = ? LIMIT 1", [(int)$userID, (int)$sID]);
      }
}
	/*Get Non Shared Stories From Database*/
public function iN_GetNonSharedStories($userID){
        if ($this->iN_CheckUserExist($userID) == 1) {
           $rows = DB::all("SELECT P.*, U.* FROM i_users U FORCE INDEX(ixForceUser)
                             INNER JOIN i_user_stories P FORCE INDEX(ixUserStories) ON P.uid_fk = U.iuid AND U.uStatus IN('1','3')
                             WHERE P.status = '1' AND P.uid_fk = ?", [(int)$userID]);
           return !empty($rows) ? $rows : null;
        }
}
	/*GET UPLOADED FILE IDs*/
public function iN_GetUploadedStoriesData($userID, $sID) {
        if ($this->iN_CheckStorieIDExist($userID, $sID) == 1) {
            return DB::one("SELECT * FROM i_user_stories WHERE uid_fk = ? AND s_id = ? LIMIT 1", [(int)$userID, (int)$sID]);
        } else {
            return false;
        }
}
	/*Update Storie Status*/
public function iN_InsertMyStorie($userID, $storieID, $storieText) {
        $insertStorie = '';
        if($storieText){
          $insertStorie = ", text = '".str_replace("'","''", $storieText)."'";
        }
        if ($this->iN_CheckUserExist($userID) == 1) {
            // Keep dynamic SET for text to preserve legacy path, but switch to PDO base
            $sql = "UPDATE i_user_stories SET status = '2' $insertStorie WHERE s_id = ? AND uid_fk = ?";
            DB::exec($sql, [(int)$storieID, (int)$userID]);
            return true;
        }
}
public function iN_FriendStoryPost($userID) {
    if ($this->iN_CheckUserExist($userID) == 1) {
        $sql = "SELECT S.uid_fk,
                       GROUP_CONCAT(S.s_id) as pics,
                       MAX(S.s_id) as s_id,
                       MAX(S.uploaded_file_path) as uploaded_file_path,
                       MAX(S.upload_tumbnail_file_path) as upload_tumbnail_file_path,
                       MAX(S.uploaded_x_file_path) as uploaded_x_file_path,
                       MAX(S.uploaded_file_ext) as uploaded_file_ext,
                       MAX(S.text) as text,
                       MAX(S.text_style) as text_style,
                       MAX(S.created) as created,
                       MAX(S.story_type) as story_type,
                       MAX(U.iuid) as iuid,
                       MAX(U.i_username) as i_username,
                       MAX(U.i_user_fullname) as i_user_fullname,
                       MAX(U.user_avatar) as user_avatar,
                       MAX(U.user_verified_status) as user_verified_status
                FROM i_user_stories S
                JOIN i_users U ON S.uid_fk = U.iuid
                JOIN i_friends F ON S.uid_fk = F.fr_two
                WHERE U.uStatus = '3'
                  AND FROM_UNIXTIME(S.created) > (NOW() - INTERVAL 24 HOUR)
                  AND F.fr_one = ?
                  AND (F.fr_status IN ('fri','me','1','flwr','subscriber'))
                GROUP BY S.uid_fk
                ORDER BY S.uid_fk ASC LIMIT 20";
        $rows = DB::all($sql, [(int)$userID]);
        return !empty($rows) ? $rows : null;
    }
}
public function iN_FriendStoryPostListAll($userID) {
    if ($this->iN_CheckUserExist($userID) == 1) {
        $sql = "SELECT S.uid_fk,
                       GROUP_CONCAT(S.s_id) as pics,
                       MAX(S.s_id) as s_id,
                       MAX(S.uploaded_file_path) as uploaded_file_path,
                       MAX(S.upload_tumbnail_file_path) as upload_tumbnail_file_path,
                       MAX(S.uploaded_x_file_path) as uploaded_x_file_path,
                       MAX(S.uploaded_file_ext) as uploaded_file_ext,
                       MAX(S.text) as text,
                       MAX(S.text_style) as text_style,
                       MAX(S.created) as created,
                       MAX(S.story_type) as story_type,
                       MAX(U.iuid) as iuid,
                       MAX(U.i_username) as i_username,
                       MAX(U.i_user_fullname) as i_user_fullname,
                       MAX(U.user_avatar) as user_avatar,
                       MAX(U.user_verified_status) as user_verified_status
                FROM i_user_stories S
                JOIN i_users U ON S.uid_fk = U.iuid
                JOIN i_friends F ON S.uid_fk = F.fr_two
                WHERE U.uStatus = '3'
                  AND FROM_UNIXTIME(S.created) > (NOW() - INTERVAL 24 HOUR)
                  AND F.fr_one = ?
                  AND (F.fr_status IN ('fri','me','1','flwr','subscriber'))
                GROUP BY S.uid_fk
                ORDER BY S.uid_fk ASC LIMIT 100";
        $rows = DB::all($sql, [(int)$userID]);
        return !empty($rows) ? $rows : null;
    }
}
public function iN_GetLastSharedStatus($userID) {
        $row = DB::one("SELECT upload_tumbnail_file_path FROM i_user_stories WHERE uid_fk = ? ORDER BY s_id DESC LIMIT 1", [(int)$userID]);
        $lastStoryImg = isset($row['upload_tumbnail_file_path']) ? $row['upload_tumbnail_file_path'] : NULL;
        if (isset($lastStoryImg)) {
            return $lastStoryImg;
        } else {
            return false;
        }
}
	/*GET UPLOADED FILE IDs*/
public function iN_GetUploadedStoriesDataS($sID) {
        $row = DB::one("SELECT * FROM i_user_stories WHERE s_id = ? LIMIT 1", [(int)$sID]);
        return $row ?: null;
}
	/*Check Storie ID Exist*/
public function iN_CheckStorieIDExistJustID($userID, $sID){
        if ($this->iN_CheckUserExist($userID) == 1) {
          return (bool) DB::col("SELECT 1 FROM i_user_stories WHERE s_id = ? LIMIT 1", [(int)$sID]);
        }
}
	/*Check Storie Seen Before*/
public function iN_CheckStorieSeenBefore($userID, $storieID){
        if ($this->iN_CheckStorieIDExistJustID($userID, $storieID)) {
            $exists = (bool) DB::col("SELECT 1 FROM i_stories_seen WHERE i_seen_uid_fk = ? AND i_seen_storie_id = ? LIMIT 1", [(int)$userID, (int)$storieID]);
            if($exists){
               return false;
           }else{
              return true;
           }
        }
}
	/*Insert Storie Seen*/
public function iN_InsertStorieSeen($userID, $storieID) {
        if ($this->iN_CheckStorieSeenBefore($userID, $storieID) == 1) {
            DB::exec("INSERT INTO i_stories_seen (i_seen_uid_fk, i_seen_storie_id) VALUES (?,?)", [(int)$userID, (int)$storieID]);
        }
}
	/*All User Storie Posts*/
    public function iN_AllUserStoriePosts($userID, $lastPostID, $showingPost) {
        $params = [(int)$userID];
        $more = '';
        if (!empty($lastPostID)) { $more = ' AND P.s_id < ? '; $params[] = (int)$lastPostID; }
        $limit = (int)$showingPost;
        $sql = "SELECT P.*, U.*
                FROM i_users U FORCE INDEX(ixForceUser)
                INNER JOIN i_user_stories P FORCE INDEX(ixUserStories)
                  ON P.uid_fk = U.iuid AND U.uStatus IN('1','3')
                WHERE P.status IN('1','2') AND P.uid_fk = ? $more
                ORDER BY P.s_id DESC
                LIMIT $limit";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
	/*CALCULATE HOW MANY USER SEEN USER STORIES*/
    public function iN_GetStorySeenCount($userID, $storieID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_stories_seen WHERE i_seen_storie_id = ? AND i_seen_uid_fk <> ?", [(int)$storieID, (int)$userID]);
            return $val !== false ? $val : '0';
        } else {
            return false;
        }
    }
	/*Get Storie Seen List*/
    public function iN_GetUploadedStoriesSeenData($userID,$storieID) {
        if ($this->iN_CheckStorieIDExistJustID($userID, $storieID)) {
            $rows = DB::all("SELECT * FROM i_stories_seen WHERE i_seen_storie_id = ? AND i_seen_uid_fk <> ?", [(int)$storieID, (int)$userID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*CALCULATE HOW MANY USERS MALE OR FEMALE*/
    public function iN_GetTotalUserByGender($userID,$genderType) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_users WHERE user_gender = ?", [(string)$genderType]);
            return $val !== false ? $val : '0';
        } else {
            return false;
        }
    }
	/*CALCULATE HOW MANY USERS Activated*/
    public function iN_GetTotalEmailVerifiedUsers($userID,$verifyStatus) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_users WHERE email_verify_status = ?", [(string)$verifyStatus]);
            return $val !== false ? $val : '0';
        } else {
            return false;
        }
    }
	/*All Storie Posts*/
    public function iN_AllTypeStoriePostsList($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $limit = (int)$paginationLimit;
            $sql = "SELECT P.*, U.* FROM i_user_stories P FORCE INDEX(ixUserStories)
                    INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.uid_fk = U.iuid
                    WHERE P.status IN('1','2') ORDER BY P.s_id DESC LIMIT $start_from, $limit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Check Storie ID Exist*/
    public function iN_CheckStorieIDExistForAdmin($userID, $sID){
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            return (bool) DB::col("SELECT 1 FROM i_user_stories WHERE s_id = ? LIMIT 1", [(int)$sID]);
        }
    }
	/*GET UPLOADED FILE IDs*/
    public function iN_GetUploadedStoriesDataForAdmin($sID) {
        return DB::one("SELECT * FROM i_user_stories WHERE s_id = ? LIMIT 1", [(int)$sID]);
    }
    /*Insert Product*/
    public function iN_InsertNewProduct($userID, $productName, $productPrice, $productDescription, $productDescriptionInfo, $productFiles, $productNameSlug, $productType, $productLimSlots, $productQuestion) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $time = time();
            DB::exec(
                "INSERT INTO i_user_product_posts (pr_name, pr_price, pr_files, pr_desc, pr_desc_info, pr_created_time, iuid_fk, pr_status, pr_seen_time, pr_number_of_sales, pr_name_slug, product_type, pr_slots_number, pr_question_answer)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [
                    (string)$productName, (string)$productPrice, (string)$productFiles, (string)$productDescription, (string)$productDescriptionInfo,
                    $time, (int)$userID, '1', '0', '0', (string)$productNameSlug, (string)$productType,
                    $productLimSlots !== '' ? (string)$productLimSlots : null,
                    $productQuestion !== '' ? (string)$productQuestion : null
                ]
            );
            return true;
        }
    }
	/*Insert Product*/
    public function iN_InsertNewProductDownloadable($userID, $productName, $productPrice, $productDescription, $productDescriptionInfo, $productFiles, $productNameSlug, $productType, $productFile) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $time = time();
            DB::exec(
                "INSERT INTO i_user_product_posts (pr_name, pr_price, pr_files, pr_desc, pr_desc_info, pr_created_time, iuid_fk, pr_status, pr_seen_time, pr_number_of_sales, pr_name_slug, product_type, pr_downlodable_files)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [
                    (string)$productName, (string)$productPrice, (string)$productFiles, (string)$productDescription, (string)$productDescriptionInfo,
                    $time, (int)$userID, '1', '0', '0', (string)$productNameSlug, (string)$productType, (string)$productFile
                ]
            );
            return true;
        }
    }
	/*Insert Product*/
    public function iN_InsertNewProductLiveEventTicket($userID, $productName, $productPrice, $productDescription, $productDescriptionInfo, $productFiles, $productNameSlug, $productType, $slotsNumber, $questionAnswer) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $time = time();
            $slots = ($slotsNumber === '' || $slotsNumber === null) ? null : (string)$slotsNumber;
            $qAns  = ($questionAnswer === '' || $questionAnswer === null) ? null : (string)$questionAnswer;
            DB::exec(
                "INSERT INTO i_user_product_posts (pr_name, pr_price, pr_files, pr_desc, pr_desc_info, pr_created_time, iuid_fk, pr_status, pr_seen_time, pr_number_of_sales, pr_name_slug, product_type, pr_slots_number, pr_question_answer)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                [
                    (string)$productName, (string)$productPrice, (string)$productFiles, (string)$productDescription, (string)$productDescriptionInfo,
                    $time, (int)$userID, '1', '0', '0', (string)$productNameSlug, (string)$productType, $slots, $qAns
                ]
            );
            return true;
        }
    }
	/*Check Product ID exist*/
    public function iN_CheckProductIDExist($userID, $productID){
        if ($this->iN_CheckUserExist($userID) == 1) {
            return (bool) DB::col("SELECT 1 FROM i_user_product_posts WHERE iuid_fk = ? AND pr_id = ? LIMIT 1", [(int)$userID, (int)$productID]);
        }
    }
	/*Check Product ID EXIST Non Loged In User*/
    public function iN_CheckProductIDExistFromURL($productID){
        return (bool) DB::col("SELECT 1 FROM i_user_product_posts WHERE pr_id = ? LIMIT 1", [(int)$productID]);
    }
	/*Product List*/
    public function iN_ProductLists($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $limit = (int)$paginationLimit;
            $sql = "SELECT DISTINCT P.*, U.*
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_user_product_posts P FORCE INDEX(ixProduct)
                      ON P.iuid_fk = U.iuid AND U.uStatus IN('1','3')
                    WHERE P.pr_status IN('0','1') AND P.iuid_fk = ?
                    ORDER BY P.pr_id DESC
                    LIMIT $start_from, $limit";
            $rows = DB::all($sql, [(int)$userID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*User Total Subscribers*/
    public function iN_UserTotalProducts($userID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_product_posts WHERE iuid_fk = ?", [(int)$userID]);
            return $val !== false ? $val : '0';
        }
    }
    public function iN_UpdateProductStatus($userID, $productID,$productStatus){
        if($this->iN_CheckProductIDExist($userID, $productID) == 1){
            DB::exec("UPDATE i_user_product_posts SET pr_status = ? WHERE iuid_fk = ? AND pr_id = ?", [(string)$productStatus, (int)$userID, (int)$productID]);
            return true;
        }
    }
	/*GET product Details*/
    public function iN_ProductDetails($userID, $productID){
        if($this->iN_CheckProductIDExist($userID, $productID) == 1){
            return DB::one("SELECT * FROM i_user_product_posts WHERE iuid_fk = ? AND pr_id = ? LIMIT 1", [(int)$userID, (int)$productID]);
        }
    }

	/*Get Product Details From URL ID*/
    public function iN_GetProductDetailsByID($productID) {
        if ($this->iN_CheckProductIDExistFromURL($productID) == '1') {
            $sql = "SELECT DISTINCT P.*, U.*
                    FROM i_user_product_posts P FORCE INDEX(ixProduct)
                    INNER JOIN i_users U FORCE INDEX(ixForceUser)
                      ON P.iuid_fk = U.iuid AND U.uStatus IN('1','3') AND pr_status IN('1')
                    WHERE P.pr_id = ?
                    ORDER BY P.pr_id";
            return DB::one($sql, [(int)$productID]);
        } else {return false;}
    }
	/*Insert Product*/
    public function iN_InsertUpdatedProduct($userID,$productID, $productName, $productPrice, $productDescription, $productDescriptionInfo, $productNameSlug,$productLimSlots,$productQuestion) {
        if ($this->iN_CheckProductIDExist($userID, $productID) == 1) {
            $slots = ($productLimSlots !== '' && $productLimSlots !== null) ? (string)$productLimSlots : null;
            $qAns  = ($productQuestion !== '' && $productQuestion !== null) ? (string)$productQuestion : null;
            DB::exec(
                "UPDATE i_user_product_posts SET pr_name = ?, pr_price = ?, pr_desc = ?, pr_desc_info = ?, pr_name_slug = ?, pr_slots_number = ?, pr_question_answer = ? WHERE iuid_fk = ? AND pr_id = ?",
                [(string)$productName, (string)$productPrice, (string)$productDescription, (string)$productDescriptionInfo, (string)$productNameSlug, $slots, $qAns, (int)$userID, (int)$productID]
            );
            return true;
        }
    }
	/*Delete Post From Data if Storage Deleting*/
    public function iN_DeleteProductFromDataifStorage($userID, $productID){
        if ($this->iN_CheckProductIDExist($userID, $productID) == 1) {
            DB::exec("DELETE FROM i_user_product_posts WHERE pr_id = ? AND iuid_fk = ?", [(int)$productID, (int)$userID]);
            return true;
        } else {
            return false;
        }
    }
	/*Delete Post From Data*/
    public function iN_DeleteProduct($userID, $productID) {
        if ($this->iN_CheckProductIDExist($userID, $productID) == 1) {
            $getPostFileIDs = $this->iN_ProductDetails($userID,$productID);
            $postFileIDs = isset($getPostFileIDs['pr_files']) ? $getPostFileIDs['pr_files'] : NULL;
            $s3 = DB::one("SELECT s3_status, was_status, ocean_status FROM i_configurations WHERE configuration_id = 1");
            $s3Status = $s3['s3_status'] ?? '0';
            $WasStatus = $s3['was_status'] ?? '0';
            $oceanStatus = $s3['ocean_status'] ?? '0';
            if ($postFileIDs && $s3Status != '1' && $oceanStatus != '1' && $WasStatus != '1') {
                $trimValue = rtrim($postFileIDs, ',');
                $explodeFiles = array_unique(explode(',', $trimValue));
                foreach ($explodeFiles as $explodeFile) {
                    $theFileID = $this->iN_GetUploadedFileDetails($explodeFile);
                    $uploadedFileID = $theFileID['upload_id'] ?? null;
                    $uploadedFilePath = $theFileID['uploaded_file_path'] ?? null;
                    $uploadedTumbnailFilePath = $theFileID['upload_tumbnail_file_path'] ?? null;
                    $uploadedFilePathX = $theFileID['uploaded_x_file_path'] ?? null;
                    if ($uploadedFilePath) @unlink('../' . $uploadedFilePath);
                    if ($uploadedFilePathX) @unlink('../' . $uploadedFilePathX);
                    if ($uploadedTumbnailFilePath) @unlink('../' . $uploadedTumbnailFilePath);
                    if ($uploadedFileID) {
                        DB::exec("DELETE FROM i_user_uploads WHERE upload_id = ? AND iuid_fk = ?", [(int)$uploadedFileID, (int)$userID]);
                    }
                }
            }
            if ($this->iN_CheckIsAdmin($userID) == 1) {
                DB::exec("DELETE FROM i_user_product_posts WHERE pr_id = ?", [(int)$productID]);
                return true;
            } else {
                DB::exec("DELETE FROM i_user_product_posts WHERE pr_id = ? AND iuid_fk = ?", [(int)$productID, (int)$userID]);
                return true;
            }
        } else {
            return false;
        }
    }
	/*Insert Story BG*/
    public function iN_InsertNewStoryBg($userID, $filePath){
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("INSERT INTO i_story_text_bg(st_bg_img_url, st_bg_status) VALUES (?, '0')", [(string)$filePath]);
            return true;
        }
    }
	/*All Story Bg Image*/
    public function iN_AllStoryBgList($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $limit = (int)$paginationLimit;
            $sql = "SELECT * FROM i_story_text_bg ORDER BY st_bg_id DESC LIMIT $start_from, $limit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Total Story Bg Image*/
    public function iN_TotalStoryBgImage() {
        $val = DB::col("SELECT COUNT(*) FROM i_story_text_bg");
        return $val !== false ? $val : 0;
    }
	/*Check Story Bg ID Exist*/
    public function iN_CheckStoryBgIdExist($bgID){
        return (bool) DB::col("SELECT 1 FROM i_story_text_bg WHERE st_bg_id = ? LIMIT 1", [(int)$bgID]);
    }
	/*Update Story Bg Status*/
    public function iN_UpdateStoryBgStatus($userID, $mode, $bgID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckStoryBgIdExist($bgID) == 1) {
            DB::exec("UPDATE i_story_text_bg SET st_bg_status = ? WHERE st_bg_id = ?", [(string)$mode, (int)$bgID]);
            return true;
        } else {
            return false;
        }
    }
	/*Delete Sticker*/
    public function iN_DeleteStoryBg($userID, $storyBgIMG) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckStoryBgIdExist($storyBgIMG) == 1) {
            DB::exec("DELETE FROM i_story_text_bg WHERE st_bg_id = ?", [(int)$storyBgIMG]);
            return true;
        } else {
            return false;
        }
    }
	/*Get All Story Background Images*/
    public function iN_GetStoryBgImages(){
        $rows = DB::all("SELECT * FROM i_story_text_bg WHERE st_bg_status = '1'");
        return !empty($rows) ? $rows : null;
    }
	/*Get Choosed Bg Image*/
    public function iN_GetChoosedBgImage(){
        $row = DB::one("SELECT st_bg_img_url FROM i_story_text_bg WHERE choosed_status = 'ok' LIMIT 1");
        return $row ? ($row['st_bg_img_url'] ?? false) : false;
    }
	/*Get Story Background Image from ID*/
    public function iN_GetBgImageByID($bgID){
        if($this->iN_CheckStoryBgIdExist($bgID) == 1){
            $row = DB::one("SELECT st_bg_img_url FROM i_story_text_bg WHERE st_bg_id = ? LIMIT 1", [(int)$bgID]);
            return $row ? ($row['st_bg_img_url'] ?? false) : false;
        }
    }
	/*Share My Text Story*/
    public function iN_InsertTextStory($userID, $bgID ,$storyText) {
        if($this->iN_CheckStoryBgIdExist($bgID) == 1 && $this->iN_CheckUserExist($userID) == 1){
            $time = time();
            $bgImage = $this->iN_GetBgImageByID($bgID);
            DB::exec(
                "INSERT INTO i_user_stories (uid_fk, uploaded_file_path, upload_tumbnail_file_path, uploaded_x_file_path, uploaded_file_ext, text, text_style, created, status, story_type)
                 VALUES (?,?,?,?,?,?,?,?,?,?)",
                [(int)$userID, (string)$bgImage, (string)$bgImage, (string)$bgImage, 'png', (string)$storyText, 'one', $time, '1', 'textStory']
            );
            return true;
        }
    }
	/*Get Other Products*/
    public function iN_OtherProductsByUserID($productOwnerID){
        if($this->iN_CheckUserExist($productOwnerID) == 1){
            $sql = "SELECT DISTINCT P.*, U.*
                    FROM i_user_product_posts P FORCE INDEX(ixProduct)
                    INNER JOIN i_users U FORCE INDEX(ixForceUser)
                      ON P.iuid_fk = U.iuid AND U.uStatus IN('1','3') AND pr_status IN('1')
                    WHERE P.iuid_fk = ?
                    ORDER BY P.pr_id DESC LIMIT 4";
            $rows = DB::all($sql, [(int)$productOwnerID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Check IP Exist Before*/
    public function iN_CheckIPExist($ip, $pID){
        $exists = (bool) DB::col("SELECT 1 FROM i_product_seen_time WHERE p_uid_ip = ? AND p_id = ? LIMIT 1", [(string)$ip, (int)$pID]);
        return $exists ? false : true;
    }
	/*Check product Seen Before*/
    public function iN_CheckProductSeenBeforeByUserID($userID, $pID){
        $exists = (bool) DB::col("SELECT 1 FROM i_product_seen_time WHERE p_id = ? AND p_s_iuid_fk = ? LIMIT 1", [(int)$pID, (int)$userID]);
        return $exists ? false : true;
     }
	 /*Insert User Product Seen*/
     public function iN_InsertVisitor($ip,$uProductID,$lUserID) {
         $time = time();
         if($this->iN_CheckIPExist($ip, $uProductID) == 1){
             if(isset($lUserID) && !empty($lUserID) && $lUserID != '' && $this->iN_CheckUserExist($lUserID) == 1 && $this->iN_CheckProductSeenBeforeByUserID($lUserID, $uProductID) == 1){
                 DB::exec("INSERT INTO i_product_seen_time(p_uid_ip, p_id, p_seen_time, p_s_iuid_fk) VALUES (?,?,?,?)", [(string)$ip, (int)$uProductID, $time, (int)$lUserID]);
             }else{
                 DB::exec("INSERT INTO i_product_seen_time(p_uid_ip, p_id, p_seen_time) VALUES (?,?,?)", [(string)$ip, (int)$uProductID, $time]);
             }
         }
     }
	/*Product Seen Count*/
    public function iN_TotalProductSeen($pID) {
        $val = DB::col("SELECT COUNT(*) FROM i_product_seen_time WHERE p_id = ?", [(int)$pID]);
        return $val !== false ? $val : '0';
    }
	/*Product Sell Count*/
    public function iN_TotalProductSell($pID) {
        $val = DB::col("SELECT COUNT(*) FROM i_user_payments WHERE paymet_product_id = ? AND payment_status = 'ok' AND payed_iuid_fk IS NOT NULL AND amount IS NOT NULL", [(int)$pID]);
        return $val !== false ? $val : '0';
    }
	/*Check item purchased before*/
    public function iN_CheckItemPurchasedBefore($userID, $productID){
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckProductIDExistFromURL($productID)){
            $exists = (bool) DB::col("SELECT 1 FROM i_user_payments WHERE paymet_product_id = ? AND payer_iuid_fk = ? AND amount IS NOT NULL LIMIT 1", [(int)$productID, (int)$userID]);
            return $exists ? true : false;
        }
    }
	public function download($url,$fake) {
		set_time_limit(0); // Prevent timeouts
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$r = curl_exec($ch);
		curl_close($ch);

		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header("Content-Length: " . filesize($url));
		header('Content-Disposition: attachment; filename='.basename($fake));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($url));
		@ob_end_clean();
		readfile($url);
		echo $r;
	}
	/*Product Sell List*/
    public function iN_SalesProductList($userID,$paginationLimit,$page){
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $limit = (int)$paginationLimit;
            $sql = "SELECT DISTINCT P.*,U.*,X.*
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_user_payments P FORCE INDEX (ixPayment) ON P.payed_iuid_fk = U.iuid
                    INNER JOIN i_user_product_posts X FORCE INDEX (ixProduct) ON X.pr_id = P.paymet_product_id AND U.uStatus IN('1','3') AND P.payment_type IN('product')
                    WHERE P.payed_iuid_fk = ? AND X.pr_id = P.paymet_product_id
                    ORDER BY P.payed_iuid_fk DESC
                    LIMIT $start_from, $limit";
            $rows = DB::all($sql, [(int)$userID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*User Total Subscribers*/
    public function iN_UserTotalProductsSales($userID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_payments WHERE payed_iuid_fk = ? AND payment_type IN('product')", [(int)$userID]);
            return $val !== false ? $val : '0';
        }
    }
	/*Product Sell List*/
    public function iN_MyPurchasedProductList($userID,$paginationLimit,$page){
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckUserExist($userID) == 1) {
            $limit = (int)$paginationLimit;
            $sql = "SELECT DISTINCT P.*,U.*,X.*
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_user_payments P FORCE INDEX (ixPayment) ON P.payer_iuid_fk = U.iuid
                    INNER JOIN i_user_product_posts X FORCE INDEX (ixProduct) ON X.pr_id = P.paymet_product_id AND U.uStatus IN('1','3') AND P.payment_type IN('product')
                    WHERE P.payer_iuid_fk = ? AND X.pr_id = P.paymet_product_id AND P.payment_status = 'ok' AND P.payed_iuid_fk IS NOT NULL
                    ORDER BY P.payed_iuid_fk DESC
                    LIMIT $start_from, $limit";
            $rows = DB::all($sql, [(int)$userID]);
            return !empty($rows) ? $rows : null;
        }
    }
	/*User Total Subscribers*/
    public function iN_UserTotalPurchasedProducts($userID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_payments WHERE payer_iuid_fk = ? AND payment_type IN('product')", [(int)$userID]);
            return $val !== false ? $val : '0';
        }
    }
	/*Shop Data*/
    public function iN_ShopData($userID, $id){
        if ($this->iN_CheckUserExist($userID) == 1) {
            $row = DB::one("SELECT status FROM i_shop_configuration WHERE id = ? LIMIT 1", [(int)$id]);
            return $row['status'] ?? null;
        }
    }
	/*Update Post Create Mod*/
    public function iN_UpdateShopFeatureStatus($userID, $mod, $ID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("UPDATE i_shop_configuration SET status = ? WHERE id = ?", [(string)$mod, (int)$ID]);
            return true;
        } else {
            return false;
        }
    }
	/*Story Data*/
    public function iN_StoryData($userID, $id){
        if ($this->iN_CheckUserExist($userID) == 1) {
            $row = DB::one("SELECT sstatus FROM i_story_configuration WHERE id = ? LIMIT 1", [(int)$id]);
            return $row['sstatus'] ?? null;
        }
    }
    public function iN_GetStoryData($id){
        $data = DB::one("SELECT * FROM i_story_configuration WHERE id = ?", [(int)$id]);
        return $data ?: false;
    }
	/*Update Post Create Mod*/
    public function iN_UpdateStoryFeatureStatus($userID, $mod, $ID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("UPDATE i_story_configuration SET sstatus = ? WHERE id = ?", [(string)$mod, (int)$ID]);
            return true;
        } else {
            return false;
        }
    }
	/*All Announcement*/
    public function iN_AllCreatedAnnouncement($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $limit = (int)$paginationLimit;
            $sql = "SELECT * FROM i_announcement ORDER BY a_id DESC LIMIT $start_from, $limit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*User Total Subscribers*/
    public function iN_HowManyUserSeeAnnouncement($userID, $announcementID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_announcement_seen WHERE a_id_fk = ?", [(int)$announcementID]);
            return $val !== false ? $val : '0';
        }
    }
	/*Total ANNOUNCEMENT*/
    public function iN_TotalAnnouncement() {
        $val = DB::col("SELECT COUNT(*) FROM i_announcement");
        return $val !== false ? $val : 0;
    }
	/*Insert New Announcement*/
    public function iN_InsertAnnouncement($userID, $announcementText, $annoucementStatus, $announcementType) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $time = time();
            DB::exec("INSERT INTO i_announcement (a_text, a_who_see, a_status, a_created_time) VALUES (?,?,?,?)",
                [(string)$announcementText, (string)$announcementType, (string)$annoucementStatus, $time]
            );
            return true;
        }
    }
	/*Update Sticker Status*/
    public function iN_UpdateAnnouncementStatus($userID, $mode, $sID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_announcement SET a_status = ? WHERE a_id = ?", [(string)$mode, (int)$sID]);
            return true;
        } else {
            return false;
        }
    }
	/*Check Announcement ID Exist*/
    public function iN_CheckAnnouncementIDExist($aID) {
        return (bool) DB::col("SELECT 1 FROM i_announcement WHERE a_id = ? LIMIT 1", [(int)$aID]);
    }
	/*Delete Announcement*/
    public function iN_DeleteAnnouncement($userID, $aID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckAnnouncementIDExist($aID) == 1) {
            DB::exec("DELETE FROM i_announcement WHERE a_id = ?", [(int)$aID]);
            DB::exec("DELETE FROM i_announcement_seen WHERE a_id_fk = ?", [(int)$aID]);
            return true;
        } else {
            return false;
        }
    }
	/*Get Sticker Details From ID*/
    public function iN_GetAnnouncementDetailsFromID($userID, $aID) {
        if($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckAnnouncementIDExist($aID) == 1){
            return DB::one("SELECT * FROM i_announcement WHERE a_id = ?", [(int)$aID]);
        }
    }
	/*Update Announcement*/
    public function iN_UpdateAnnouncement($userID, $aID, $announcementText, $annoucementStatus, $announcementType) {
        if($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckAnnouncementIDExist($aID) == 1){
            DB::exec("UPDATE i_announcement SET a_status = ?, a_text = ?, a_who_see = ? WHERE a_id = ?",
                [(string)$annoucementStatus, (string)$announcementText, (string)$announcementType, (int)$aID]
            );
            return true;
        }else{
            return false;
        }
    }
	/*Get Announcement*/
    public function iN_ShowAnnouncement(){
        return DB::one("SELECT * FROM i_announcement WHERE a_status = 'yes' ORDER BY a_id DESC LIMIT 1") ?: null;
    }
	/*Accept Announcement*/
    public function iN_AnnouncementAccepted($userID, $announceID) {
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckAnnouncementIDExist($announceID) == 1){
            $time = time();
            DB::exec("INSERT INTO i_announcement_seen (a_id_fk, iuid_fk, a_seen_time) VALUES (?,?,?)", [(int)$announceID, (int)$userID, $time]);
            return true;
        }
    }
	/*Accept Announcement*/
    public function iN_CheckUserAcceptedAnnouncementBefore($userID, $announceID) {
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckAnnouncementIDExist($announceID) == 1){
            $exists = (bool) DB::col("SELECT 1 FROM i_announcement_seen WHERE a_id_fk = ? AND iuid_fk = ? LIMIT 1", [(int)$announceID, (int)$userID]);
            return $exists ? false : true;
        }
    }
	/*All User Storie Posts*/
    public function iN_AllUserProductPosts($category, $lastPostID, $showingPost) {
        $conds = ["P.pr_status IN('1')"]; $params = [];
        if (!empty($category)) { $conds[] = 'P.product_type = ?'; $params[] = (string)$category; }
        if (!empty($lastPostID)) { $conds[] = 'P.pr_id < ?'; $params[] = (int)$lastPostID; }
        $where = implode(' AND ', $conds);
        $limit = (int)$showingPost;
        $sql = "SELECT P.*, U.* FROM i_user_product_posts P FORCE INDEX(ixProduct)
                INNER JOIN i_users U FORCE INDEX(ixForceUser)
                  ON P.iuid_fk = U.iuid AND U.uStatus IN('1','3')
                WHERE $where
                ORDER BY P.pr_id DESC
                LIMIT $limit";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
	/*Total Products*/
    public function iN_TotalProducts($userID) {
        if($this->iN_CheckIsAdmin($userID) == 1){
            $val = DB::col("SELECT COUNT(*) FROM i_user_product_posts");
            return $val !== false ? $val : 0;
        }
    }
	/*Total Products BY USER*/
    public function iN_TotalProductByUser($userID) {
        if($this->iN_CheckUserExist($userID) == 1){
            $val = DB::col("SELECT COUNT(*) FROM i_user_product_posts WHERE iuid_fk = ?", [(int)$userID]);
            return $val !== false ? $val : 0;
        }
    }
	/*CALCULATE HOW MANY USERS MALE OR FEMALE*/
    public function iN_GetTotalProductByCategory($userID,$prType) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_product_posts WHERE product_type = ?", [(string)$prType]);
            return $val !== false ? $val : '0';
        } else {
            return false;
        }
    }
	/*All Posts*/
    public function iN_AllTypeOfProductList($userID, $paginationLimit, $page, $searchValue) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        $limit = (int)$paginationLimit;
        $where = '';
        $params = [];
        if (!empty($searchValue)) {
            $like = '%' . $searchValue . '%';
            $where = 'WHERE (P.pr_name LIKE ? OR P.pr_name_slug LIKE ?)';
            $params = [$like, $like];
        }
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $sql = "SELECT P.*, U.*
                    FROM i_user_product_posts P FORCE INDEX(ixProduct)
                    INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.iuid_fk = U.iuid AND U.uStatus IN('1','3')
                    $where
                    ORDER BY P.pr_id DESC LIMIT $start_from, $limit";
            $rows = DB::all($sql, $params);
            return !empty($rows) ? $rows : null;
        }
    }
    public function iN_DeleteProductAdmin($userID, $productID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckProductIDExistFromURL($productID) == 1) {
            $getPostFileIDs = $this->iN_GetProductDetailsByID($productID);
            $postFileIDs = isset($getPostFileIDs['pr_files']) ? $getPostFileIDs['pr_files'] : NULL;
            $s3 = DB::one("SELECT s3_status, was_status, ocean_status FROM i_configurations WHERE configuration_id = 1");
            $s3Status = $s3['s3_status'] ?? '0';
            $WasStatus = $s3['was_status'] ?? '0';
            $oceanStatus = $s3['ocean_status'] ?? '0';
            if ($postFileIDs && $s3Status != '1' && $oceanStatus != '1' && $WasStatus != '1') {
                $trimValue = rtrim($postFileIDs, ',');
                $explodeFiles = array_unique(explode(',', $trimValue));
                foreach ($explodeFiles as $explodeFile) {
                    $theFileID = $this->iN_GetUploadedFileDetails($explodeFile);
                    $uploadedFileID = $theFileID['upload_id'] ?? null;
                    $uploadedFilePath = $theFileID['uploaded_file_path'] ?? null;
                    $uploadedTumbnailFilePath = $theFileID['upload_tumbnail_file_path'] ?? null;
                    $uploadedFilePathX = $theFileID['uploaded_x_file_path'] ?? null;
                    if ($uploadedFilePath) @unlink('../' . $uploadedFilePath);
                    if ($uploadedFilePathX) @unlink('../' . $uploadedFilePathX);
                    if ($uploadedTumbnailFilePath) @unlink('../' . $uploadedTumbnailFilePath);
                    if ($uploadedFileID) DB::exec("DELETE FROM i_user_uploads WHERE upload_id = ? AND iuid_fk = ?", [(int)$uploadedFileID, (int)$userID]);
                }
            }
            if ($this->iN_CheckIsAdmin($userID) == 1) {
                DB::exec("DELETE FROM i_user_product_posts WHERE pr_id = ?", [(int)$productID]);
                return true;
            } else {
                DB::exec("DELETE FROM i_user_product_posts WHERE pr_id = ? AND iuid_fk = ?", [(int)$productID, (int)$userID]);
                return true;
            }
        } else {
            return false;
        }
    }
	/*Show profile posts by selection*/
    public function iN_AllUserProfilePostsByChooseAudios($uid, $lastPostID, $showingPost) {
        $params = [(int)$uid, (int)$uid]; // For user_likes JOIN and WHERE clause
        $morePost = '';
        if (!empty($lastPostID)) { $morePost = ' AND P.post_id < ?'; $params[] = (int)$lastPostID; }
        $limit = (int)$showingPost;

        // OPTIMIZED: Added LEFT JOINs for likes count, comments count, and user like status
        $sql = "SELECT P.*, U.*,
                       MAX(A.upload_id) AS upload_id,
                       MAX(A.iuid_fk) AS iuid_fk,
                       MAX(A.uploaded_file_path) AS uploaded_file_path,
                       MAX(A.upload_tumbnail_file_path) AS upload_tumbnail_file_path,
                       MAX(A.uploaded_x_file_path) AS uploaded_x_file_path,
                       MAX(A.uploaded_file_ext) AS uploaded_file_ext,
                       MAX(A.upload_time) AS upload_time,
                       MAX(A.ip) AS ip,
                       MAX(A.upload_type) AS upload_type,
                       MAX(A.upload_status) AS upload_status,
                       IFNULL(likes.total_likes, 0) AS total_likes,
                       IFNULL(comments.total_comments, 0) AS total_comments,
                       IF(user_likes.like_id IS NOT NULL, 1, 0) AS liked_by_user
                FROM i_friends F FORCE INDEX(ixFriend)
                INNER JOIN i_posts P FORCE INDEX(ixForcePostOwner) ON P.post_owner_id = F.fr_two
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.post_owner_id = U.iuid
                    AND U.uStatus IN ('1','3') AND F.fr_status IN ('me','flwr','subscriber')
                INNER JOIN i_user_uploads A FORCE INDEX(iuPostOwner) ON P.post_owner_id = A.iuid_fk
                LEFT JOIN (
                    SELECT post_id_fk, COUNT(*) AS total_likes
                    FROM i_post_likes
                    GROUP BY post_id_fk
                ) likes ON P.post_id = likes.post_id_fk
                LEFT JOIN (
                    SELECT comment_post_id_fk, COUNT(*) AS total_comments
                    FROM i_post_comments
                    GROUP BY comment_post_id_fk
                ) comments ON P.post_id = comments.comment_post_id_fk
                LEFT JOIN i_post_likes user_likes ON P.post_id = user_likes.post_id_fk AND user_likes.iuid_fk = ?
                WHERE P.post_owner_id = ? AND P.post_pined = '0' AND P.post_file <> ''
                  AND A.uploaded_file_ext = 'mp3' AND FIND_IN_SET(A.upload_id, P.post_file)
                  $morePost
                GROUP BY P.post_id
                ORDER BY P.post_id DESC
                LIMIT $limit";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
	/*Show profile posts by selection*/
    public function iN_AllUserProfilePostsByChooseVideos($uid, $lastPostID, $showingPost) {
        $params = [(int)$uid, (int)$uid]; // For user_likes JOIN and WHERE clause
        $morePost = '';
        if (!empty($lastPostID)) { $morePost = ' AND P.post_id < ?'; $params[] = (int)$lastPostID; }
        $limit = (int)$showingPost;

        // OPTIMIZED: Added LEFT JOINs for likes count, comments count, and user like status
        $sql = "SELECT P.*, U.*,
                       MAX(A.upload_id) AS upload_id,
                       MAX(A.iuid_fk) AS iuid_fk,
                       MAX(A.uploaded_file_path) AS uploaded_file_path,
                       MAX(A.upload_tumbnail_file_path) AS upload_tumbnail_file_path,
                       MAX(A.uploaded_x_file_path) AS uploaded_x_file_path,
                       MAX(A.uploaded_file_ext) AS uploaded_file_ext,
                       MAX(A.upload_time) AS upload_time,
                       MAX(A.ip) AS ip,
                       MAX(A.upload_type) AS upload_type,
                       MAX(A.upload_status) AS upload_status,
                       IFNULL(likes.total_likes, 0) AS total_likes,
                       IFNULL(comments.total_comments, 0) AS total_comments,
                       IF(user_likes.like_id IS NOT NULL, 1, 0) AS liked_by_user
                FROM i_friends F FORCE INDEX(ixFriend)
                INNER JOIN i_posts P FORCE INDEX(ixForcePostOwner) ON P.post_owner_id = F.fr_two
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.post_owner_id = U.iuid
                    AND U.uStatus IN ('1','3') AND F.fr_status IN ('me','flwr','subscriber')
                INNER JOIN i_user_uploads A FORCE INDEX(iuPostOwner) ON P.post_owner_id = A.iuid_fk
                LEFT JOIN (
                    SELECT post_id_fk, COUNT(*) AS total_likes
                    FROM i_post_likes
                    GROUP BY post_id_fk
                ) likes ON P.post_id = likes.post_id_fk
                LEFT JOIN (
                    SELECT comment_post_id_fk, COUNT(*) AS total_comments
                    FROM i_post_comments
                    GROUP BY comment_post_id_fk
                ) comments ON P.post_id = comments.comment_post_id_fk
                LEFT JOIN i_post_likes user_likes ON P.post_id = user_likes.post_id_fk AND user_likes.iuid_fk = ?
                WHERE P.post_owner_id = ? AND P.post_file <> ''
                  AND A.uploaded_file_ext = 'mp4' AND FIND_IN_SET(A.upload_id, P.post_file)
                  $morePost
                GROUP BY P.post_id
                ORDER BY P.post_id DESC
                LIMIT $limit";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
    /*Show profile posts by selection (Reels)*/
    public function iN_AllUserProfilePostsByChooseReels($uid, $lastPostID, $showingPost) {
        $params = [(int)$uid];
        $morePost = '';
        if (!empty($lastPostID)) { $morePost = ' AND P.post_id < ?'; $params[] = (int)$lastPostID; }
        $limit = (int)$showingPost;
        $sql = "SELECT DISTINCT P.*, U.*
                FROM i_friends F FORCE INDEX(ixFriend)
                INNER JOIN i_posts P FORCE INDEX(ixForcePostOwner) ON P.post_owner_id = F.fr_two
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.post_owner_id = U.iuid
                    AND U.uStatus IN ('1','3') AND F.fr_status IN ('me','flwr','subscriber')
                WHERE P.post_owner_id = ? AND P.post_type = 'reels' $morePost
                ORDER BY P.post_id DESC
                LIMIT $limit";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
	/*Show profile posts by selection*/
    public function iN_AllUserProfilePostsByChoosePhotos($uid, $lastPostID, $showingPost) {
        $params = [(int)$uid, (int)$uid]; // For user_likes JOIN and WHERE clause
        $morePost = '';
        if (!empty($lastPostID)) { $morePost = ' AND P.post_id < ?'; $params[] = (int)$lastPostID; }
        $limit = (int)$showingPost;

        // OPTIMIZED: Added LEFT JOINs for likes count, comments count, and user like status
        $sql = "SELECT P.*, U.*,
                       MAX(A.upload_id) AS upload_id,
                       MAX(A.iuid_fk) AS iuid_fk,
                       MAX(A.uploaded_file_path) AS uploaded_file_path,
                       MAX(A.upload_tumbnail_file_path) AS upload_tumbnail_file_path,
                       MAX(A.uploaded_x_file_path) AS uploaded_x_file_path,
                       MAX(A.uploaded_file_ext) AS uploaded_file_ext,
                       MAX(A.upload_time) AS upload_time,
                       MAX(A.ip) AS ip,
                       MAX(A.upload_type) AS upload_type,
                       MAX(A.upload_status) AS upload_status,
                       IFNULL(likes.total_likes, 0) AS total_likes,
                       IFNULL(comments.total_comments, 0) AS total_comments,
                       IF(user_likes.like_id IS NOT NULL, 1, 0) AS liked_by_user
                FROM i_friends F FORCE INDEX(ixFriend)
                INNER JOIN i_posts P FORCE INDEX(ixForcePostOwner) ON P.post_owner_id = F.fr_two
                INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.post_owner_id = U.iuid
                    AND U.uStatus IN ('1','3') AND F.fr_status IN ('me','flwr','subscriber')
                INNER JOIN i_user_uploads A FORCE INDEX(iuPostOwner) ON P.post_owner_id = A.iuid_fk
                LEFT JOIN (
                    SELECT post_id_fk, COUNT(*) AS total_likes
                    FROM i_post_likes
                    GROUP BY post_id_fk
                ) likes ON P.post_id = likes.post_id_fk
                LEFT JOIN (
                    SELECT comment_post_id_fk, COUNT(*) AS total_comments
                    FROM i_post_comments
                    GROUP BY comment_post_id_fk
                ) comments ON P.post_id = comments.comment_post_id_fk
                LEFT JOIN i_post_likes user_likes ON P.post_id = user_likes.post_id_fk AND user_likes.iuid_fk = ?
                WHERE P.post_owner_id = ? AND P.post_file <> ''
                  AND A.uploaded_file_ext IN ('gif','GIF','jpg','JPG','jpeg','JPEG','png','PNG')
                  AND FIND_IN_SET(A.upload_id, P.post_file)
                  $morePost
                GROUP BY P.post_id
                ORDER BY P.post_id DESC
                LIMIT $limit";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
	/*Show Products*/
    public function iN_AllUserProfileProductPosts($uid, $lastPostID, $showingPost){
        $params = [(int)$uid];
        $more = '';
        if (!empty($lastPostID)) { $more = ' AND P.pr_id < ? '; $params[] = (int)$lastPostID; }
        $limit = (int)$showingPost;
        $sql = "SELECT P.*, U.*
                FROM i_user_product_posts P FORCE INDEX(ixProduct)
                INNER JOIN i_users U FORCE INDEX(ixForceUser)
                  ON P.iuid_fk = U.iuid AND U.uStatus IN('1','3')
                WHERE P.iuid_fk = ? $more ORDER BY P.pr_id DESC LIMIT $limit";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : false;
    }
	/*Move My Point*/
    public function iN_MoveMyPoint($userID){
       if($this->iN_CheckUserExist($userID) == 1){
            $getAffilateEarnedPoint = $this->iN_GetUserDetails($userID);
            $point = $getAffilateEarnedPoint['affilate_earnings'];
            DB::exec("UPDATE i_users SET affilate_earnings = '0', wallet_points = wallet_points + ? WHERE iuid = ?", [(string)$point, (int)$userID]);
            return true;
       }
    }
    public function iN_CalculatePreviousMonthEarning($userID){
        $val = DB::col("SELECT SUM(user_earning) FROM i_user_payments WHERE payment_status = 'ok' AND payed_iuid_fk = ? AND FROM_UNIXTIME(payment_time) BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01 00:00:00') AND DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d 23:59:59')", [(int)$userID]);
        return $val !== false ? $val : '0.00';
    }
	/*Total Premium Earnings Weekly*/
    public function iN_WeeklyTotalPremiumEarningUser($userID) {
        $val = DB::col("SELECT SUM(user_earning) FROM i_user_payments WHERE payment_status = 'ok' AND payed_iuid_fk = ? AND WEEK(FROM_UNIXTIME(payment_time)) = WEEK(NOW())", [(int)$userID]);
        return $val !== false ? $val : '0.00';
    }
	/*Total Premium Earnings Current Day*/
    public function iN_CurrentDayTotalPremiumEarningUser($userID) {
        $val = DB::col("SELECT SUM(user_earning) FROM i_user_payments WHERE payment_status = 'ok' AND payed_iuid_fk = ? AND DAY(FROM_UNIXTIME(payment_time)) = DAY(CURDATE())", [(int)$userID]);
        return $val !== false ? $val : '0.00';
    }
	/*Total Premium Earnings Current Month*/
    public function iN_CurrentMonthTotalPremiumEarningUser($userID) {
        $val = DB::col("SELECT SUM(user_earning) FROM i_user_payments WHERE payment_status = 'ok' AND payed_iuid_fk = ? AND MONTH(FROM_UNIXTIME(payment_time)) = MONTH(CURDATE())", [(int)$userID]);
        return $val !== false ? $val : '0.00';
    }
	/*Total Story Bg Image*/
    public function iN_TotalSocialNetworks() {
        $val = DB::col("SELECT COUNT(*) FROM i_social_networks");
        return $val !== false ? $val : 0;
    }
	/*All Story Bg Image*/
    public function iN_AllSocialNetworkList($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $limit = (int)$paginationLimit;
            $sql = "SELECT * FROM i_social_networks ORDER BY id DESC LIMIT $start_from, $limit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Insert New Announcement*/
    public function iN_InsertNewSocialSite($userID, $newSocialSite, $newSocialSiteKey, $newSocialSiteStatus, $newSocialSiteSVGCode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec(
                "INSERT INTO i_social_networks (social_icon, skey, place_holder, status) VALUES (?,?,?,?)",
                [(string)$newSocialSiteSVGCode, (string)$newSocialSiteKey, (string)$newSocialSite, (string)$newSocialSiteStatus]
            );
            return true;
        }
    }
	/*Check Story Bg ID Exist*/
    public function iN_CheckSocialSiteIdExist($sID){
		return (bool) DB::col("SELECT 1 FROM i_social_networks WHERE id = ? LIMIT 1", [(int)$sID]);
     }
	 /*Check Story Bg ID Exist*/
    public function iN_CheckWebsiteSocialSiteIdExist($sID){
		return (bool) DB::col("SELECT 1 FROM i_website_social_networks WHERE id = ? LIMIT 1", [(int)$sID]);
     }
	/*Update Story Bg Status*/
    public function iN_UpdateSocialSiteStatus($userID, $mode, $sID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckSocialSiteIdExist($sID) == 1) {
			DB::exec("UPDATE i_social_networks SET status = ? WHERE id = ?", [(string)$mode, (int)$sID]);
			return true;
        } else {
            return false;
        }
    }
	/*Get Social Link Details By ID*/
    public function iN_GetSocialLinkDetails($userID, $ID){
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckSocialSiteIdExist($ID) == 1) {
			$data = DB::one("SELECT * FROM i_social_networks WHERE id = ?", [(int)$ID]);
			return $data ?: false;
        } else {
            return false;
        }
    }
	/*Insert New Announcement*/
    public function iN_UpdateSocialSite($userID, $socialSID, $newSocialSite, $newSocialSiteKey, $newSocialSiteStatus, $newSocialSiteSVGCode) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckSocialSiteIdExist($socialSID) == 1) {
			DB::exec("UPDATE i_social_networks SET social_icon = ?, skey = ?, place_holder = ?, status = ? WHERE id = ?",
				[(string)$newSocialSiteSVGCode,(string)$newSocialSiteKey,(string)$newSocialSite,(string)$newSocialSiteStatus,(int)$socialSID]
			);
			return true;
        }
    }
	/*Delete question*/
    public function iN_DeleteSocialSite($userID, $ssID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckSocialSiteIdExist($ssID) == 1) {
			DB::exec("DELETE FROM i_social_networks WHERE id = ?", [(int)$ssID]);
			return true;
        } else {
            return false;
        }
    }
    public function iN_ShowUserSocialSitesList($userID){
        if($this->iN_CheckUserExist($userID) == 1){
            $rows = DB::all("SELECT * FROM i_social_networks WHERE status = 'yes' ORDER BY id");
            return !empty($rows) ? $rows : null;
        }else{
           return false;
        }
    }
    public function iN_GetUserProfileLinkDetails($userID, $sID){
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckSocialSiteIdExist($sID) == 1){
            $data = DB::one("SELECT * FROM i_social_user_profiles WHERE isw_id_fk = ? AND uid_fk = ?", [(int)$sID, (int)$userID]);
            return $data ?: false;
        }
    }
	/*Show User Socials*/
    public function iN_ShowUserSocialSites($userID){
        if($this->iN_CheckUserExist($userID) == 1){
            $sql = "SELECT S.* , U.*
                    FROM i_social_user_profiles S FORCE INDEX(ixSocialLink)
                    INNER JOIN i_social_networks U FORCE INDEX(ixLinks)
                      ON S.isw_id_fk = U.id AND U.status = 'yes'
                    WHERE S.uid_fk = ? AND S.s_link IS NOT NULL AND S.s_link <> '' ORDER BY U.id";
            $rows = DB::all($sql, [(int)$userID]);
            return !empty($rows) ? $rows : null;
        }else{
           return false;
        }
    }
	public function iN_IsUrl($uri) {
		if (empty($uri)) {
			return false;
		}
		if (filter_var($uri, FILTER_VALIDATE_URL)) {
			return true;
		}
		return false;
	}
	/*All User Storie Posts*/
    public function iN_SuggestedProductWidget($showingPost) {
		$showingPosts = (int)$showingPost;
		$data = null;
		$sql = "SELECT A.*
			FROM (SELECT A.*
					FROM  i_user_product_posts A
					WHERE pr_status IN('1')
					ORDER BY pr_id DESC
					LIMIT 10
					) A
				ORDER BY RAND()
				LIMIT $showingPosts";
			$rows = DB::all($sql);
			if (!empty($rows)) {
				$data = $rows;
				return $data;
			}
    }
    public function iN_GetVideoCallDetailsBetweenTwoUsersIfExistIM($chatID, $userID){
        $exists = (bool) DB::col(
            "SELECT 1 FROM i_video_call WHERE chat_id_fk = ? AND (called_uid_fk = ? OR caller_uid_fk = ?) AND accept_status = '2' LIMIT 1",
            [(int)$chatID, (int)$userID, (int)$userID]
        );
        return $exists ? true : false;
    }
     public function iN_GetVideoCallDetailsBetweenTwoUsersIfExist($chatID, $userID){
        return DB::one(
            "SELECT * FROM i_video_call WHERE chat_id_fk = ? AND (called_uid_fk = ? OR caller_uid_fk = ?) AND accept_status = '2' ORDER BY vc_id DESC LIMIT 1",
            [(int)$chatID, (int)$userID, (int)$userID]
        );
    }
	/*Video Call*/
    public function iN_InsertVideoCall($userID, $videoCallName, $calledUserID) {
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($calledUserID) == 1){
            $time = time();
            if($this->iN_CheckConversationStartedBeforeBetweenUsers($userID, $calledUserID) == 0){
                DB::exec("INSERT INTO i_chat_users (user_one, user_two) VALUES (?, ?)", [(int)$userID, (int)$calledUserID]);
                $row = DB::one("SELECT chat_id FROM i_chat_users WHERE user_one = ? AND user_two = ? ORDER BY chat_id DESC LIMIT 1", [(int)$userID, (int)$calledUserID]);
                $chatID = $row['chat_id'] ?? null;
                if ($chatID) {
                    DB::exec("INSERT INTO i_video_call (chat_id_fk, voice_call_name, called_uid_fk, caller_uid_fk, called_time) VALUES (?,?,?,?,?)",
                        [(int)$chatID, (string)$videoCallName, (int)$calledUserID, (int)$userID, $time]
                    );
                    return $chatID;
                }
            } else {
                $row = DB::one("SELECT chat_id FROM i_chat_users WHERE (user_one = ? AND user_two = ?) OR (user_one = ? AND user_two = ?) ORDER BY chat_id DESC LIMIT 1",
                    [(int)$userID, (int)$calledUserID, (int)$calledUserID, (int)$userID]
                );
                $chatID = $row['chat_id'] ?? null;
                if ($chatID) {
                    DB::exec("INSERT INTO i_video_call (chat_id_fk, voice_call_name, called_uid_fk, caller_uid_fk, called_time) VALUES (?,?,?,?,?)",
                        [(int)$chatID, (string)$videoCallName, (int)$calledUserID, (int)$userID, $time]
                    );
                    return $chatID;
                }
            }
        } else {
            return false;
        }
    }
    public function iN_CheckVideoCall($userID) {
        if($this->iN_CheckUserExist($userID) == 1){
            $row = DB::one("SELECT vc_id FROM i_video_call WHERE called_uid_fk = ? AND accept_status = '1' AND called_time >= (UNIX_TIMESTAMP(NOW()) - 120) ORDER BY called_time DESC LIMIT 1", [(int)$userID]);
            return isset($row['vc_id']) ? $row['vc_id'] : false;
        }
    }
    public function iN_CheckVideoCallStatus($userID) {
        if($this->iN_CheckUserExist($userID) == 1){
            $row = DB::one("SELECT accept_status FROM i_video_call WHERE (caller_uid_fk = ? OR called_uid_fk = ?) AND called_time >= (UNIX_TIMESTAMP(NOW()) - 300) ORDER BY called_time DESC LIMIT 1", [(int)$userID, (int)$userID]);
            return isset($row['accept_status']) ? $row['accept_status'] : false;
        }
    }
    public function iN_VideoCallDetails($vID){
        return DB::one("SELECT * FROM i_video_call WHERE vc_id = ?", [(int)$vID]);
    }
    public function iN_VideoCallAcceptDetails($vID){
        DB::exec("UPDATE i_video_call SET accept_status = '2' WHERE vc_id = ?", [(int)$vID]);
        return DB::one("SELECT * FROM i_video_call WHERE vc_id = ?", [(int)$vID]);
    }
    public function iN_CheckAndDeleteCall($userID, $channelName) {
        $exists = (bool) DB::col("SELECT 1 FROM i_video_call WHERE voice_call_name = ? LIMIT 1", [(string)$channelName]);
        if ($exists) {
            DB::exec("DELETE FROM i_video_call WHERE voice_call_name = ?", [(string)$channelName]);
            return true;
        }
        return false;
    }
    public function iN_VideoCallDeclineDetails($vID){
        DB::exec("UPDATE i_video_call SET accept_status = '3' WHERE vc_id = ?", [(int)$vID]);
        return DB::one("SELECT * FROM i_video_call WHERE vc_id = ?", [(int)$vID]);
    }
	/*Update Users Wallets*/
    public function iN_UpdateUsersWalletsForVideoCall($userID, $tiSendingUserID, $tipAmount, $netUserEarning,$adminFee,$adminEarning,$userNetEarning){
        if ($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckUserExist($tiSendingUserID) == 1) {
            $time = time();
            try {
                DB::begin();
                DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [(int)$tipAmount, (int)$userID]);
                DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [(string)$userNetEarning, (int)$tiSendingUserID]);
                DB::exec("INSERT INTO i_user_payments (payer_iuid_fk, payed_iuid_fk, payment_type, payment_time, payment_status, amount, user_earning, admin_earning, fee) VALUES (?,?,?,?,?,?,?,?,?)",
                    [(int)$userID, (int)$tiSendingUserID, 'videoCall', $time, 'ok', (string)$netUserEarning, (string)$userNetEarning, (string)$adminEarning, (string)$adminFee]
                );
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        }
    }
	/*Update Post Create Mod*/
    public function iN_UpdateVideoCallFeatureStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET video_call_feature_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Post Create Mod*/
    public function iN_UpdateWhoCanCreateVideoCallFeatureStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET who_can_careate_video_call = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Post Create Mod*/
    public function iN_UpdateIsVideoCallPaidStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET is_video_call_free = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Video Cost*/
    public function iN_UpdateVideoCost($userID, $videoCost) {
        if($this->iN_CheckUserExist($userID) == 1){
            DB::exec("UPDATE i_users SET video_call_price = ? WHERE iuid = ?", [(string)$videoCost, (int)$userID]);
            return true;
        }else{
            return false;
        }
    }
    public function iN_TotalEarningPointsInaDay($userID) {
        $userID = (int)$userID; //  DATE(FROM_UNIXTIME(pointed_time))=CURDATE()
        if($this->iN_CheckUserExist($userID) == 1){
            $val = DB::col("SELECT SUM(point) FROM i_user_point_earnings WHERE DATE(FROM_UNIXTIME(pointed_time)) = CURDATE() AND poninted_user_id = ?", [$userID]);
            return $val ? (int)$val : 0;
        }
    }
    public function iN_MovePointEarningsToPointBalance($userID, $totalPointEarned){
        if($this->iN_CheckUserExist($userID) == 1){
            try {
                DB::begin();
                DB::exec("UPDATE i_user_point_earnings SET calculated_point = '1' WHERE poninted_user_id = ?", [(int)$userID]);
                DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [(int)$totalPointEarned, (int)$userID]);
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        }else{
            return false;
        }
    }
	/*Check Who Can Seee Post*/
    public function iN_CheckWhoCanSeePost($postID){
        $row = DB::one("SELECT who_can_see FROM i_posts WHERE post_id = ?", [(int)$postID]);
        return $row ? $row['who_can_see'] : false;
     }
	/*Check Who Can Seee Post*/
    public function iN_GetPostOwnerIDFromPostID($postID){
        $row = DB::one("SELECT post_owner_id FROM i_posts WHERE post_id = ?", [(int)$postID]);
        return $row ? $row['post_owner_id'] : false;
    }
    public function iN_InsertNewTipMessage($userID, $chatID, $message, $sendedGiftMoney) {
        if ($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckChatIDExist($chatID) == '1') {
            $time = time();
            $cData = $this->iN_GetChatUserIDs($chatID);
            $chatUserOne = $cData['user_one'];
            $chatUserTwo = $cData['user_two'];
            $toID = ($chatUserOne == $userID) ? $chatUserTwo : $chatUserOne;
            DB::exec("INSERT INTO i_chat_conversations (chat_id_fk, user_one, user_two, message, time, gifMoney) VALUES (?,?,?,?,?,?)",
                [(int)$chatID, (int)$userID, (int)$toID, (string)$message, $time, (string)$sendedGiftMoney]
            );
            $sql = "SELECT * FROM (
                        SELECT DISTINCT M.con_id, M.chat_id_fk, M.user_one, M.user_two, M.message, M.gifMoney, M.private_price, M.private_status, M.file, M.sticker_url, M.gifurl, M.seen_status, M.time,
                                        C.chat_id, U.iuid, U.uStatus, U.i_username, U.i_user_fullname, U.user_gender
                        FROM i_chat_users C FORCE INDEX(ixUserChat)
                        INNER JOIN i_chat_conversations M FORCE INDEX(ixChat) ON C.chat_id = M.chat_id_fk
                        INNER JOIN i_users U FORCE INDEX(ixForceUser) ON M.user_one = U.iuid AND U.uStatus IN('1','3')
                        WHERE M.chat_id_fk = ? AND M.user_one = ? AND M.user_two = ?
                        ORDER BY M.con_id DESC LIMIT 1
                    ) t ORDER BY con_id ASC";
            $data = DB::one($sql, [(int)$chatID, (int)$userID, (int)$toID]);
            DB::exec("UPDATE i_users SET message_notification_read_status = '1' WHERE iuid = ?", [(int)$toID]);
            return $data ?: false;
        } else {
            return false;
        }
    }
    public function iN_CheckMesageIDExistForUnLock($messageID, $conversationID) {
        $exists = (bool) DB::col("SELECT 1 FROM i_chat_conversations WHERE chat_id_fk = ? AND con_id = ? LIMIT 1", [(int)$conversationID, (int)$messageID]);
        return $exists ? true : false;
    }
	/*Get Message Details By ID*/
    public function iN_GetMessageDetailsByID($messageID, $chatID){
       if($this->iN_CheckMesageIDExistForUnLock($messageID, $chatID) == 1){
           return DB::one("SELECT * FROM i_chat_conversations WHERE con_id = ? AND chat_id_fk = ?", [(int)$messageID, (int)$chatID]);
       }else{
           return false;
       }
    }
	/*Unlock Message*/
    public function iN_UnLockMessage($userID, $messageID, $chatID, $adminEarning, $userEarning,$messageOwnerID, $translatePointToMoney, $adminFee, $messagePrice){
        if($this->iN_CheckUserExist($userID) == 1 && $this->iN_CheckMesageIDExistForUnLock($messageID, $chatID) == 1){
            $time = time();
            try {
                DB::begin();
                DB::exec("INSERT INTO i_user_payments (payer_iuid_fk, payed_iuid_fk, unlocked_message_id, payment_type, payment_time, payment_status, amount, fee, admin_earning, user_earning) VALUES (?,?,?,?,?,?,?,?,?,?)",
                    [(int)$userID, (int)$messageOwnerID, (int)$messageID, 'unlockmessage', $time, 'ok', (string)$translatePointToMoney, (string)$adminFee, (string)$adminEarning, (string)$userEarning]
                );
                DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [(int)$messagePrice, (int)$userID]);
                DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [(string)$userEarning, (int)$messageOwnerID]);
                DB::exec("UPDATE i_chat_conversations SET private_status = 'opened' WHERE con_id = ? AND chat_id_fk = ?", [(int)$messageID, (int)$chatID]);
                DB::commit();
            } catch (Throwable $e) { DB::rollBack(); return false; }

            $data = DB::one("SELECT * FROM i_chat_conversations WHERE con_id = ? AND chat_id_fk = ?", [(int)$messageID, (int)$chatID]);
            return $data ?: false;
        }else{
            return FALSE;
        }
    }
	/*Update Profile Preferences (Message Send Status)*/
    public function iN_UpdateWhoCanSendYouAMessage($userID, $status) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            DB::exec("UPDATE i_users SET who_can_send_message = ? WHERE iuid = ?", [ (string)$status, (int)$userID ]);
            return true;
        }
        return false;
    }
	/*Check user Is Creator*/
    public function iN_CheckUserIsCreator($userID){
        $exists = (bool) DB::col(
            "SELECT 1 FROM i_users WHERE iuid = ? AND certification_status = '2' AND validation_status = '2' AND condition_status = '2' AND fees_status = '2' AND payout_status = '2' LIMIT 1",
            [(int)$userID]
        );
        return $exists;
    }
	/*Update Post Create Mod*/
    public function iN_UpdateSarchResultStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET show_search_result_type = ? WHERE configuration_id = 1", [ (string)$mod ]);
            return true;
        }
        return false;
    }
	/*Total Story Bg Image*/
    public function iN_TotalWebsiteSocialNetworks() {
        $val = DB::col("SELECT COUNT(*) FROM i_website_social_networks");
        return $val !== false ? (int)$val : 0;
    }
	/*All Story Bg Image*/
    public function iN_AllWebsiteSocialNetworkList($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $limit = (int)$paginationLimit;
            $sql = "SELECT * FROM i_website_social_networks ORDER BY id DESC LIMIT $start_from, $limit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Update Story Bg Status*/
    public function iN_UpdateWebsiteSocialSiteStatus($userID, $mode, $sID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckWebsiteSocialSiteIdExist($sID) == 1) {
            DB::exec("UPDATE i_website_social_networks SET status = ? WHERE id = ?", [(string)$mode, (int)$sID]);
            return true;
        } else {
            return false;
        }
    }
	/*Get Social Link Details By ID*/
    public function iN_GetWbsiteSocialLinkDetails($userID, $ID){
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckWebsiteSocialSiteIdExist($ID) == 1) {
            $data = DB::one("SELECT * FROM i_website_social_networks WHERE id = ?", [(int)$ID]);
            return $data ?: false;
        } else {
            return false;
        }
    }
	/*Insert New Announcement*/
    public function iN_UpdateWebsiteSocialSite($userID, $socialSID, $newSocialSite, $newSocialSiteKey, $newSocialSiteStatus, $newSocialSiteSVGCode) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckWebsiteSocialSiteIdExist($socialSID) == 1) {
            DB::exec("UPDATE i_website_social_networks SET social_icon = ?, skey = ?, place_holder = ?, status = ? WHERE id = ?",
                [(string)$newSocialSiteSVGCode, (string)$newSocialSiteKey, (string)$newSocialSite, (string)$newSocialSiteStatus, (int)$socialSID]
            );
            return true;
        }
    }
	/*Delete question*/
    public function iN_DeleteWebsiteSocialSite($userID, $ssID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckWebsiteSocialSiteIdExist($ssID) == 1) {
            DB::exec("DELETE FROM i_website_social_networks WHERE id = ?", [(int)$ssID]);
            return true;
        } else {
            return false;
        }
    }
    public function iN_ShowWebsiteSocialSitesList(){
        $rows = DB::all("SELECT * FROM i_website_social_networks WHERE status = 'yes' ORDER BY id");
        return !empty($rows) ? $rows : null;
    }
	/*Approve / Reject / Decline Premium Post*/
    public function iN_UpdateApprovePostStatusAuto($userID, $postID, $postOwnerID,  $approveNot) {
        if ($this->iN_CheckUserExist($postOwnerID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            $time = time();
            $stat = '1';
            $notType = 'accepted_post';
            DB::exec("UPDATE i_posts SET post_status = ? WHERE post_id = ?", [$stat, (int)$postID]);
            DB::exec("INSERT INTO i_approve_post_notification (approved_post_id, approved_post_owner_id, approve_status, approve_not, appprove_time) VALUES (?,?,?,?,?)",
                [(int)$postID, (int)$postOwnerID, '1', (string)$approveNot, $time]
            );
            DB::exec("INSERT INTO i_user_notifications (not_post_id, not_not_type, not_time, not_own_iuid, not_iuid) VALUES (?,?,?,?,?)",
                [(int)$postID, (string)$notType, $time, (int)$postOwnerID, (int)$userID]
            );
            DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE iuid = ?", [(int)$postOwnerID]);
            return true;
        } else {
            return false;
        }
    }
    /*Get All Posts For Premium Page*/
    public function iN_AllUserForPurchasedPremium($uid, $lastPostID, $showingPost) {
        $params = [(int)$uid];
        $morePost = '';
        if (!empty($lastPostID)) { $morePost = ' AND X.payment_id < ?'; $params[] = (int)$lastPostID; }
        $sql = "SELECT DISTINCT P.*, X.*, U.*
                FROM i_posts P FORCE INDEX(ixForcePostOwner)
                INNER JOIN i_users U FORCE INDEX(ixForceUser)
                  ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3')
                INNER JOIN i_user_payments X FORCE INDEX(ixPayment)
                  ON X.payed_post_id_fk = P.post_id
                WHERE X.payer_iuid_fk = ? AND P.post_status IN('1') AND P.who_can_see IN('4') $morePost
                ORDER BY X.payment_id DESC LIMIT 1";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }
    public function iN_GetAdminUserID(){
        $row = DB::one("SELECT iuid FROM i_users WHERE userType = '2' ORDER BY RAND() LIMIT 1");
        return isset($row['iuid']) ? $row['iuid'] : false;
    }
	/*Approve / Reject / Decline Premium Post*/
    public function iN_SendNotificationForPurchasedPost($userID, $postID, $postOwnerID,  $approveNot) {
        if ($this->iN_CheckUserExist($postOwnerID) == 1 && $this->iN_CheckPostIDExist($postID) == 1) {
            $time = time();
            $notType = 'purchasedYourPost';
            DB::exec("INSERT INTO i_user_notifications (not_post_id, not_not_type, not_time, not_own_iuid, not_iuid) VALUES (?,?,?,?,?)",
                [(int)$postID, (string)$notType, $time, (int)$postOwnerID, (int)$userID]
            );
            DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE iuid = ?", [(int)$postOwnerID]);
            return true;
        } else {
            return false;
        }
    }
	/*Shop Status*/
    public function iN_ShopStatus($id){
        $row = DB::one("SELECT status FROM i_shop_configuration WHERE id = ?", [(int)$id]);
        return $row['status'] ?? null;
    }
	/*Get Categories*/
    public function iN_GetCategories(){
        $rows = DB::all("SELECT * FROM i_profile_categories WHERE c_status = '1'");
        return !empty($rows) ? $rows : null;
    }
	/*Check and Get SubCategory*/
    public function iN_CheckAndGetSubCat($subCatID){
        $rows = DB::all("SELECT * FROM i_profile_sub_categories WHERE c_fk = ? AND sc_status = '1'", [(int)$subCatID]);
        return !empty($rows) ? $rows : null;
    }
	/*INSERT UPLOADED FILES FROM UPLOADS TABLE*/
    public function iN_INSERTUploadedFilesForVerification($uid, $filePath, $tumbnailPath, $fileXPath, $ext) {
        $uploadTime = time();
        $userIP = $_SERVER['REMOTE_ADDR'] ?? '';
        DB::exec(
            "INSERT INTO i_user_uploads (iuid_fk, uploaded_file_path, upload_tumbnail_file_path, uploaded_x_file_path, uploaded_file_ext, upload_time, ip, upload_type) VALUES (?,?,?,?,?,?,?, 'verification')",
            [(int)$uid, (string)$filePath, (string)$tumbnailPath, (string)$fileXPath, (string)$ext, $uploadTime, (string)$userIP]
        );
        return (int) DB::lastId();
    }
	/*Update MercadoPago Details*/
    public function iN_UpdateMercadoPagoDetails($userID, $testID, $liveID, $mercadoCurrency) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET mercadopago_test_access_id = ?, mercadopago_live_access_id = ?, mercadopago_currency = ? WHERE payment_method_id = 1",
                [(string)$testID, (string)$liveID, (string)$mercadoCurrency]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Update PayPal Sendbox Mode*/
    public function iN_UpdateMercadoPagoMode($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET mercadopago_payment_mode = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
	/*Update PayPal Status*/
    public function iN_UpdateMercadoPagoStatus($userID, $mode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_payment_methods SET mercadopago_active_pasive = ? WHERE payment_method_id = 1", [(string)$mode]);
            return true;
        } else {
            return false;
        }
    }
    /*Update DrawText Mod*/
    public function iN_UpdateFFMPEGDrawTextStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET enable_disable_drawtext = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Get Categories*/
    public function iN_GetCategoriesForAdmin(){
        $rows = DB::all("SELECT * FROM i_profile_categories");
        return !empty($rows) ? $rows : null;
    }
	/*Check and Get SubCategory*/
    public function iN_CheckAndGetSubCatForAdmin($subCatID){
        $rows = DB::all("SELECT * FROM i_profile_sub_categories WHERE c_fk = ?", [(int)$subCatID]);
        return !empty($rows) ? $rows : null;
    }
	/*Update Post Create Mod*/
    public function iN_UpdateSubCategoryStatus($userID, $mod, $iD) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_profile_sub_categories SET sc_status = ? WHERE sc_id = ?", [(string)$mod, (int)$iD]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Post Create Mod*/
    public function iN_UpdateSubCategoryKey($userID, $sKey, $iD) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_profile_sub_categories SET sc_key = ? WHERE sc_id = ?", [(string)$sKey, (int)$iD]);
            return true;
        } else {
            return false;
        }
    }
	/*Add New Subcategory Key*/
    public function iN_CreateNewSubCategory($userID, $newSubCatKey, $addToThisCategory) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("INSERT INTO i_profile_sub_categories (sc_key, c_fk, sc_status) VALUES (?,?, '0')", [(string)$newSubCatKey, (int)$addToThisCategory]);
            $data = DB::one("SELECT * FROM i_profile_sub_categories WHERE c_fk = ? ORDER BY sc_id DESC LIMIT 1", [(int)$addToThisCategory]);
            return $data ?: false;
        } else {
            return false;
        }
    }
	/*Delete SubCategory*/
    public function iN_DeleteSubCat($userID,$subCID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("DELETE FROM i_profile_sub_categories WHERE sc_id = ?", [(int)$subCID]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Category Mod*/
    public function iN_UpdateCategoryStatus($userID, $mod, $iD) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_profile_categories SET c_status = ? WHERE c_id = ?", [(string)$mod, (int)$iD]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Post Create Mod*/
    public function iN_UpdateCategoryKey($userID, $Key, $iD) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_profile_categories SET c_key = ? WHERE c_id = ?", [(string)$Key, (int)$iD]);
            return true;
        } else {
            return false;
        }
    }
	/*Delete Category*/
    public function iN_DeleteCat($userID,$subCID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("DELETE FROM i_profile_categories WHERE c_id = ?", [(int)$subCID]);
            return true;
        } else {
            return false;
        }
    }
	/*Create new Profile Category*/
    public function iN_InsertNewProfileCategory($userID, $newCategoryKey) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("INSERT INTO i_profile_categories (c_key, c_status) VALUES (?, '0')", [(string)$newCategoryKey]);
            return true;
        } else {
            return false;
        }
    }
	/*Get Which Sub for Category*/
    public function iN_GetCategoryFromSubCategory($subCatKey){
       $row = DB::one("SELECT c_fk FROM i_profile_sub_categories WHERE sc_key = ?", [(string)$subCatKey]);
       $catFK = $row['c_fk'] ?? null;
       if($catFK){
          $data = DB::one("SELECT c_key FROM i_profile_categories WHERE c_id = ?", [(int)$catFK]);
          return isset($data['c_key']) ? $data['c_key'] : false;
       }else{
          return false;
       }
    }
	/*Update Uploaded Status*/
    public function iN_UpdateUploadStatus($fileID){
       DB::exec("UPDATE i_user_uploads SET upload_status = '1' WHERE upload_id = ?", [(int)$fileID]);
    }

    public function iN_CheckLangKeyExist($langKey) {
        return (bool) DB::col("SELECT 1 FROM i_langs WHERE lang_name = ? AND lang_status = '1' LIMIT 1", [(string)$langKey]);
    }
    public function GetFileIDs(){
        $rows = DB::all("SELECT * FROM i_user_uploads WHERE upload_type = 'wall'");
        return !empty($rows) ? $rows : null;
    }
    public function GetFileIDsPost(){
        $rows = DB::all("SELECT * FROM i_posts WHERE post_file <> ''");
        return !empty($rows) ? $rows : null;
    }
		/*Get HashTags*/
    public function CheckFilePosted($hashTag) {
        $hashTag = strip_tags(trim((string)$hashTag));
        $hashtags_list = array_filter(array_map('trim', explode(',', $hashTag)));
        if (empty($hashtags_list)) { return null; }
        $conds = [];
        $params = [];
        foreach (array_unique($hashtags_list) as $ht) {
            $conds[] = 'FIND_IN_SET(LOWER(?), LOWER(post_file))';
            $params[] = mb_strtolower($ht, 'UTF-8');
        }
        $where = implode(' AND ', $conds);
        $rows = DB::all("SELECT * FROM i_posts WHERE ($where) ORDER BY post_id", $params);
        return !empty($rows) ? $rows : null;
    }
    public function GetFileTDetail($id){
        $result = DB::one("SELECT * FROM i_user_uploads WHERE upload_id = ?", [(int)$id]);
        return $result ?: false;
    }
    public function iN_InsertMentionedUsersForComment($userID, $postDetails, $postID, $dataUsername,$userPostOwnerID) {
        $mention_regex = '/@([A-Za-z0-9_]+)/i';
        $pregMatch = preg_match_all($mention_regex, $postDetails, $matches);
        if ($pregMatch) {
            $mentioned = [];
            foreach ($matches[1] as $match) {
                if ($match !== $dataUsername) {
                    $mentioned[] = $match;
                }
            }
            $mentioned = array_values(array_unique(array_filter($mentioned)));
            if (!empty($mentioned)) {
                $mentionTime = time();
                $inPlaceholders = implode(',', array_fill(0, count($mentioned), '?'));
                $params = $mentioned;
                DB::exec(
                    "INSERT INTO i_mentions (m_uid_fk, m_type, m_post_id_fk, m_user_owner, m_status, mention_type, m_time)
                     SELECT iuid, NULL, ?, ?, '1', 'comment', ? FROM i_users WHERE i_username IN ($inPlaceholders)",
                    array_merge([(int)$postID, (int)$userID, $mentionTime], $params)
                );
                DB::exec(
                    "INSERT INTO i_user_notifications (not_post_id, not_not_type, not_time, not_own_iuid, not_iuid)
                     SELECT ?, 'umentioned', ?, iuid, ? FROM i_users WHERE i_username IN ($inPlaceholders)",
                    array_merge([(int)$postID, $mentionTime, (int)$userID], $params)
                );
                DB::exec("UPDATE i_users SET notification_read_status = '1' WHERE i_username IN ($inPlaceholders)", $params);
            }
        }
    }
	/*Show profile posts by selection*/
    public function iN_AllUserProfilePostsPined($uid) {
        $sql = "SELECT DISTINCT P.*,U.*
                FROM i_friends F
                INNER JOIN i_posts P ON P.post_owner_id = F.fr_two
                INNER JOIN i_users U ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3') AND F.fr_status IN('me', 'flwr', 'subscriber')
                WHERE P.post_owner_id = ? AND P.post_pined = '1' AND P.boosted_status = '0'
                ORDER BY P.post_id DESC LIMIT 10";
        $rows = DB::all($sql, [(int)$uid]);
        return !empty($rows) ? $rows : null;
    }
	/*Premium Plan List*/
public function iN_BoostPlans() {
        $rows = DB::all("SELECT * FROM i_boost_post_plans WHERE plan_status = '1'");
        return !empty($rows) ? $rows : null;
    }
	/*Check Premium Plan Exist*/
    public function CheckBoostPlanExist($planID) {
        return (bool) DB::col("SELECT 1 FROM i_boost_post_plans WHERE plan_id = ? LIMIT 1", [(int)$planID]);
    }
	/*Get Boost Plan Details bY Boost ID*/
    public function iN_GetBoostPostDetails($boostPlanID){
        $row = DB::one("SELECT * FROM i_boost_post_plans WHERE plan_id = ?", [(int)$boostPlanID]);
        return $row ?: false;
    }
	/*Insert New Announcement*/
    public function iN_InsertNewWebSocialSite($userID, $newSocialSite, $newSocialSiteKey, $newSocialSiteStatus, $newSocialSiteSVGCode) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec(
                "INSERT INTO i_website_social_networks (social_icon, skey, place_holder, status) VALUES (?,?,?,?)",
                [(string)$newSocialSiteSVGCode, (string)$newSocialSiteKey, (string)$newSocialSite, (string)$newSocialSiteStatus]
            );
            return true;
        }
    }
	/*Insert Boost Payment*/
    public function iN_BoostInsert($userID, $boostPostID, $planAmount,$boostPlanID,$viewTime) {
        if($this->iN_CheckUserExist($userID) == '1'){
            $time = time();
            try {
                DB::begin();
                DB::exec("INSERT INTO i_boosted_posts (iuid_fk, post_id_fk, boost_type, view_count, status, started_at) VALUES (?,?,?,?, 'yes', ?)",
                    [(int)$userID, (int)$boostPostID, (int)$boostPlanID, (int)$viewTime, $time]
                );
                $row = DB::one("SELECT boost_id FROM i_boosted_posts WHERE iuid_fk = ? ORDER BY boost_id DESC LIMIT 1", [(int)$userID]);
                $boostID = $row['boost_id'] ?? null;
                DB::exec("INSERT INTO i_user_payments (payer_iuid_fk, payed_post_id_fk, payment_type, payment_time, payment_status, amount) VALUES (?,?, 'boostPost', ?, 'ok', ?)",
                    [(int)$userID, (int)$boostPostID, $time, (string)$planAmount]
                );
                DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [(int)$planAmount, (int)$userID]);
                if ($boostID) {
                    DB::exec("UPDATE i_posts SET boosted_status = '1', boost_id_fk = ? WHERE post_id = ? AND post_owner_id = ?", [(int)$boostID, (int)$boostPostID, (int)$userID]);
                }
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }

        }
    }
    /*Check Post Boosted Before*/
    public function iN_CheckPostBoostedBefore($userID, $postID){
        if($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckPostIDExist($postID) == '1'){
            $exists = (bool) DB::col("SELECT 1 FROM i_boosted_posts WHERE post_id_fk = ? AND iuid_fk = ? LIMIT 1", [(int)$postID, (int)$userID]);
            return $exists ? true : false;
        }
    }
	/*Get Boosted Post Details*/
    public function iN_GetBoostedPostDetails($postID){
        return DB::one("SELECT * FROM i_boosted_posts WHERE post_id_fk = ? ORDER BY boost_id DESC LIMIT 1", [(int)$postID]) ?: false;
    }
    public function iN_AllUserForBoostedPosts($uid, $lastPostID, $showingPost) {
        $params = [(int)$uid];
        $morePost = '';
        if (!empty($lastPostID)) { $morePost = ' AND P.post_id < ?'; $params[] = (int)$lastPostID; }
        $limit = (int)$showingPost;
        $sql = "SELECT P.*, U.*
                FROM i_friends F
                INNER JOIN i_posts P ON P.post_owner_id = F.fr_two
                INNER JOIN i_users U ON P.post_owner_id = U.iuid
                WHERE P.post_owner_id = ? AND P.boosted_status = '1' $morePost
                  AND U.uStatus IN ('1','3') AND F.fr_status IN ('me')
                ORDER BY P.post_id DESC
                LIMIT $limit";
        $rows = DB::all($sql, $params);
        return !empty($rows) ? $rows : null;
    }

	/*Check boost ID Exist*/
    public function iN_CheckBoostIDExist($userID, $boostPostID){
        $exists = (bool) DB::col("SELECT 1 FROM i_boosted_posts WHERE boost_id = ? AND iuid_fk = ? LIMIT 1", [(int)$boostPostID, (int)$userID]);
        return $exists ? true : false;
    }
	/*Update Boost Post Status*/
    public function iN_UpdateBoosPostStatus($userID, $bPostID, $bpStatus){
        if($this->iN_CheckUserExist($userID) == '1' && $this->iN_CheckBoostIDExist($userID, $bPostID) == '1'){
            DB::exec("UPDATE i_boosted_posts SET status = ? WHERE boost_id = ? AND iuid_fk = ?", [(string)$bpStatus, (int)$bPostID, (int)$userID]);
            return true;
        }else{
            return false;
        }
    }
    /*Check Boosted Post Seen Before*/
    public function iN_CheckBoostedPostSeenBefore($userID, $ip, $boostID){
        $exists = (bool) DB::col("SELECT 1 FROM i_boosted_post_seen_counter WHERE bp_id_fk = ? AND iuid_fk = ? AND ip = ? LIMIT 1", [(int)$boostID, (int)$userID, (string)$ip]);
        return $exists ? false : true;
    }
	/*Boost Seen Counter*/
    public function iN_BoostPostSeenCounter($userID, $boostID, $ip){
        if(!is_numeric($boostID) || empty($boostID)){
            return false;
        }

        if($this->iN_CheckuserExist($userID) == '1' && $this->iN_CheckBoostedPostSeenBefore($userID, $ip, $boostID) == '1'){
            $time = time();
            DB::exec("INSERT INTO i_boosted_post_seen_counter (iuid_fk, bp_id_fk, ip, bp_seen_time) VALUES (?,?,?,?)", [(int)$userID, (int)$boostID, (string)$ip, $time]);
            return true;
        }else{
            return false;
        }
    }
	/*Show Boosted Post*/
    public function iN_ShowBoostedPost($userID, $showingPost) {
        $sql = "SELECT P.*, U.*, B.*
                FROM i_posts P
                INNER JOIN i_users U ON P.post_owner_id = U.iuid
                LEFT JOIN i_friends F ON P.post_owner_id = F.fr_two
                LEFT JOIN i_boosted_posts B ON P.post_owner_id = B.iuid_fk
                WHERE P.boosted_status = '1'
                  AND P.post_owner_id <> ?
                  AND U.uStatus IN ('1','3')
                  AND (F.fr_status IS NULL OR F.fr_status NOT IN ('flwr','subscriber'))
                ORDER BY RAND()
                LIMIT 1";
        $rows = DB::all($sql, [(int)$userID]);
        return !empty($rows) ? $rows : null;
    }
	/*Show Boosted Post*/
    public function iN_ShowBoostedPostNoneLogin() {
        $sql = "SELECT P.*, U *, B.*
                FROM i_posts P
                INNER JOIN i_users U ON P.post_owner_id = U.iuid
                LEFT JOIN i_friends F ON P.post_owner_id = F.fr_two
                LEFT JOIN i_boosted_posts B ON P.post_owner_id = B.iuid_fk
                WHERE P.boosted_status = '1'
                  AND U.uStatus IN ('1','3')
                  AND (F.fr_status IS NULL OR F.fr_status NOT IN ('flwr','subscriber'))
                ORDER BY RAND() LIMIT 1";
        $rows = DB::all($sql);
        return !empty($rows) ? $rows : null;
    }
	/*Check User Have Boosted Post*/
    public function iN_CheckHaveBoostedPostAllTheSite(){
        $val = DB::col("SELECT COUNT(*) FROM i_boosted_posts WHERE status = 'yes'");
        return $val !== false ? $val : '0';
    }
	/*Check User Have Boosted Post*/
    public function iN_CheckHaveBoostedPost($userID){
        if($this->iN_CheckuserExist($userID) == '1'){
            $val = DB::col("SELECT COUNT(*) FROM i_boosted_posts WHERE iuid_fk = ? AND status IN('no','yes')", [(int)$userID]);
            return $val !== false ? $val : '0';
        }else{
            return false;
        }
    }
	/*Total Products*/
    public function iN_TotalBoostedPost($userID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_boosted_posts BP INNER JOIN i_posts P ON BP.post_id_fk = P.post_id WHERE EXISTS (SELECT 1 FROM i_posts WHERE post_id = BP.post_id_fk)");
            return $val !== false ? $val : '0';
        }
    }
	/*Count How Many Times Seen Boosted Post by ID*/
    public function iN_CountSeenBoostedPostbyID($userID,$boostedPostID){
        if($this->iN_CheckuserExist($userID) == '1'){
            $val = DB::col("SELECT COUNT(*) FROM i_boosted_post_seen_counter WHERE bp_id_fk = ?", [(int)$boostedPostID]);
            return $val !== false ? $val : '0';
        }else{
            return false;
        }
    }
	/*Update Boosted Post Status*/
    public function iN_UpdateBoostedPostStatus($userPostOwnerID, $boostID){
        DB::exec("UPDATE i_boosted_posts SET status = 'no' WHERE boost_id = ? AND iuid_fk = ?", [(int)$boostID, (int)$userPostOwnerID]);
    }
	/*Show All Boosted Post*/
    public function iN_ShowAllBoostedPost($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if($this->iN_CheckIsAdmin($userID) == 1){
            $limit = (int)$paginationLimit;
            $sql = "SELECT P.*,U.*,B.* FROM i_posts P
                    INNER JOIN i_users U ON P.post_owner_id = U.iuid AND U.uStatus IN('1','3')
                    INNER JOIN i_boosted_posts B ON P.boost_id_fk = B.boost_id
                    WHERE P.boosted_status = '1'
                    ORDER BY B.boost_id DESC LIMIT $start_from, $limit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Check boost ID Exist*/
    public function iN_CheckBoostExist($boostPostID){
        return (bool) DB::col("SELECT 1 FROM i_boosted_posts WHERE boost_id = ? LIMIT 1", [(int)$boostPostID]);
     }
	/*Update Story Bg Status*/
    public function iN_UpdateBoostPostStatus($userID, $mode, $bgID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckBoostExist($bgID) == 1) {
            DB::exec("UPDATE i_boosted_posts SET status = ? WHERE boost_id = ?", [(string)$mode, (int)$bgID]);
            return true;
        } else {
            return false;
        }
    }
	/*Get Boosted Post Details*/
    public function iN_GetBoostedDetailsByID($postID){
        $row = DB::one("SELECT * FROM i_boosted_posts WHERE boost_id = ?", [(int)$postID]);
        return $row ?: false;
     }
	/*Delete Boosted Post*/
    public function iN_DeleteBoostedPost($userID, $boostID){
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $bData = $this->iN_GetBoostedDetailsByID($boostID);
            $boostedPostID = isset($bData['post_id_fk']) ? $bData['post_id_fk'] : NULL;
            DB::exec("UPDATE i_posts SET boost_id_fk = '0' WHERE boost_id_fk = ?", [(int)$boostID]);
            DB::exec("DELETE FROM i_boosted_posts WHERE boost_id = ?", [(int)$boostID]);
            DB::exec("DELETE FROM i_user_payments WHERE payed_post_id_fk = ? AND payment_type = 'boostPost'", [(int)$boostID]);
            return true;
        } else {
            return false;
        }
    }
	/*Total Admin Boost Post Earnings*/
    public function iN_TotalBoostEarnings($userID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT SUM(amount) FROM i_user_payments WHERE payment_status = 'ok' AND payment_type = 'boostPost'");
            // SUM returns NULL when no rows match; normalize to string '0.00'
            return ($val === null || $val === false) ? '0.00' : (string)$val;
        }
    }
    /*Total Boost Earnings Weekly*/
    public function iN_WeeklyTotalBoostEarning() {
        $val = DB::col("SELECT SUM(amount) FROM i_user_payments WHERE payment_status = 'ok' AND payment_type = 'boostPost' AND WEEK(FROM_UNIXTIME(payment_time)) = WEEK(NOW())");
        return ($val === null || $val === false) ? '0.00' : (string)$val;
    }
	/*Total Boost Earnings Current Day*/
    public function iN_CurrentDayTotalBoostEarning() {
        $val = DB::col("SELECT SUM(amount) FROM i_user_payments WHERE payment_status = 'ok' AND payment_type = 'boostPost' AND DAY(FROM_UNIXTIME(payment_time)) = DAY(CURDATE())");
        return ($val === null || $val === false) ? '0.00' : (string)$val;
    }
	/*Total Boost Earnings Current Month*/
    public function iN_CurrentMonthTotalBoostEarning() {
        $val = DB::col("SELECT SUM(amount) FROM i_user_payments WHERE payment_status = 'ok' AND payment_type = 'boostPost' AND MONTH(FROM_UNIXTIME(payment_time)) = MONTH(CURDATE())");
        return ($val === null || $val === false) ? '0.00' : (string)$val;
    }
	/*Latest 5 Subscriptions List*/
    public function iN_SubscriptionsListData($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if($this->iN_CheckIsAdmin($userID) == 1){
            $limit = (int)$paginationLimit;
            $sql = "SELECT S.*, U.* FROM i_user_subscriptions S
                    INNER JOIN i_users U ON S.subscribed_iuid_fk = U.iuid AND U.uStatus IN('1','3')
                    WHERE S.status = 'active' ORDER BY S.subscription_id DESC
                    LIMIT $start_from, $limit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Calculate All Posts*/
    public function iN_CalculateAllSubscriptions() {
        $val = DB::col("SELECT COUNT(*) FROM i_user_subscriptions");
        return $val !== false ? $val : '0';
    }
	/*Calculate All Active Subscriptions*/
    public function iN_CalculateAllActiveSubscriptions($type) {
        $val = DB::col("SELECT COUNT(*) FROM i_user_subscriptions WHERE status = ?", [(string)$type]);
        return $val !== false ? $val : '0';
    }
	/*Calculate All Inactive Subscriptions*/
    public function iN_CalculateAllInactiveSubscriptions($type) {
        $val = DB::col("SELECT COUNT(*) FROM i_user_subscriptions WHERE status = ?", [(string)$type]);
        return $val !== false ? $val : '0';
    }
	/*Calculate All Declined Subscriptions*/
    public function iN_CalculateAllDeclinedSubscriptions($type) {
        $val = DB::col("SELECT COUNT(*) FROM i_user_subscriptions WHERE status = ?", [(string)$type]);
        return $val !== false ? $val : '0';
    }
	/*Premium Plan List*/
    public function iN_BoostPlanList() {
        $rows = DB::all("SELECT * FROM i_boost_post_plans");
        return !empty($rows) ? $rows : null;
    }
	/*Update Boost Plan Status*/
    public function iN_UpdateBoostPlanStatus($userID, $mod, $planID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckBoostPlanExist($planID) == 1) {
            DB::exec("UPDATE i_boost_post_plans SET plan_status = ? WHERE plan_id = ?", [(string)$mod, (int)$planID]);
            return true;
        } else {
            return false;
        }
    }
	/*Delete Boost Plan*/
    public function iN_DeleteBoostPlanFromData($userID, $planID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckBoostPlanExist($planID) == 1) {
            DB::exec("DELETE FROM i_boost_post_plans WHERE plan_id = ?", [(int)$planID]);
            return true;
        } else {
            return false;
        }
    }
	/*Update BOOST Plan*/
    public function iN_UpdateBoostPlanFromID($userID, $planKey, $planViewtime, $planAmount, $planSVGIcon, $plandID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckBoostPlanExist($plandID)) {
            DB::exec("UPDATE i_boost_post_plans SET plan_name_key = ?, plan_amount = ?, view_time = ?, plan_icon = ? WHERE plan_id = ?",
                [(string)$planKey, (string)$planAmount, (string)$planViewtime, (string)$planSVGIcon, (int)$plandID]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Insert New BOOST PLAN*/
    public function iN_InsertNewBOOSTPlan($userID, $planKey, $planViewTime, $planAmount, $planIcon) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("INSERT INTO i_boost_post_plans (plan_name_key, plan_icon, plan_amount, view_time, plan_status) VALUES (?,?,?,?, '0')",
                [(string)$planKey, (string)$planIcon, (string)$planAmount, (string)$planViewTime]
            );
            return true;
        } else {
            return false;
        }
    }
	/*User Total Point Payments*/
    public function iN_UserTotalTransactions($userID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_payments");
            return $val !== false ? $val : '0';
        }
    }
	/*Latest 5 Subscriptions List*/
    public function iN_TransactionsList($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if($this->iN_CheckIsAdmin($userID) == 1){
            $limit = (int)$paginationLimit;
            $sql = "SELECT P.*, U.*
                    FROM i_users U FORCE INDEX(ixForceUser)
                    INNER JOIN i_user_payments P FORCE INDEX(ixPayment)
                      ON P.payer_iuid_fk = U.iuid AND U.uStatus IN('1','3')
                    WHERE P.payment_type IN('post','live_stream','tips','live_gift','videoCall','boostPost','point','profile','product','frame')
                    ORDER BY P.payment_id DESC
                    LIMIT $start_from, $limit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Current Month Earn Calculate*/
    public function iN_CalculateTypePayment($type) {
        $val = DB::col("SELECT SUM(amount) FROM i_user_payments WHERE payment_type = ?", [(string)$type]);
        return $val !== false ? $val : '0';
    }
	/*Current Month Earn Calculate*/
    public function iN_CalculatePaymentSpecific($type) {
        $val = DB::col("SELECT SUM(admin_earning) FROM i_user_payments WHERE payment_type = ?", [(string)$type]);
        return $val !== false ? $val : '0';
    }
	/*INSERT UPLOADED FILES FROM UPLOADS TABLE*/
    public function iN_INSERTUploadedScreenShotForPaymentComplete($uid, $filePath, $tumbnailPath, $fileXPath, $ext) {
        $uploadTime = time();
        $userIP = $_SERVER['REMOTE_ADDR'] ?? '';
        DB::exec(
            "INSERT INTO i_user_uploads (iuid_fk, uploaded_file_path, upload_tumbnail_file_path, uploaded_x_file_path, uploaded_file_ext, upload_time, ip, upload_type) VALUES (?,?,?,?,?,?,?, 'bankPayment')",
            [(int)$uid, (string)$filePath, (string)$tumbnailPath, (string)$fileXPath, (string)$ext, $uploadTime, (string)$userIP]
        );
        return (int) DB::lastId();
    }
	/*Insert  A New Verification Request*/
    public function iN_InsertNewBankPaymentVerificationRequest($userID, $cardIDPhoto, $planAmount, $planPoint, $planID) {
        if ($this->iN_CheckUserExist($userID) == 1) {
            $time = time();
            DB::exec("INSERT INTO i_bank_payments (iuid_fk, screen_photo, request_time) VALUES (?,?,?)", [(int)$userID, (string)$cardIDPhoto, $time]);
            DB::exec("INSERT INTO i_user_payments (payer_iuid_fk, payment_type, payment_option, payment_time, payment_status, amount, credit_plan_id, bank_payment_image) VALUES (?, 'point','bank', ?, 'pending', ?, ?, ?)",
                [(int)$userID, $time, (string)$planAmount, (int)$planID, (string)$cardIDPhoto]
            );
            return true;
        } else {
            return false;
        }
    }
	/*Get Payment Details*/
    public function iN_GetPaymentDetailsByID($paymentID, $userID){
        if($this->iN_CheckIsAdmin($userID) == 1){
            $data = DB::one("SELECT * FROM i_user_payments WHERE payment_id = ?", [(int)$paymentID]);
            return $data ?: false;
        }
    }
	/*Get Image By ID*/
public function iN_GetImageByID($base_url, $userID , $ImageID){
        if($this->iN_CheckIsAdmin($userID) == 1){
            $data = DB::one("SELECT uploaded_file_path FROM i_user_uploads WHERE upload_id = ?", [(int)$ImageID]);
            if($data){
                $imageFile = $data['uploaded_file_path'] ?? null;
                $s3 = DB::one("SELECT s3_status, was_status, ocean_status, s3_bucket, s3_region, was_bucket, was_region, ocean_space_name, ocean_region FROM i_configurations WHERE configuration_id = 1");
                $s3Status = $s3['s3_status'] ?? '0';
                $wasStatus = $s3['was_status'] ?? '0';
                $oceanStatus = $s3['ocean_status'] ?? '0';
                if ($s3Status == 1) {
                    return 'https://' . $s3['s3_bucket'] . '.s3.' . $s3['s3_region'] . '.amazonaws.com/' . $imageFile;
                }else if($wasStatus == 1){
                    return 'https://' . $s3['was_bucket'] . '.s3.' . $s3['was_region'] . '.wasabisys.com/' . $imageFile;
                }else if($oceanStatus == 1){
                    return 'https://'.$s3['ocean_space_name'].'.'.$s3['ocean_region'].'.digitaloceanspaces.com/'. $imageFile;
                } else {
                    return $base_url . $imageFile;
                }
            }
        }
        }
	/*Approve PAYMENT*/
    public function iN_InsertApprove($userID, $payerID, $planID, $imageID,$paymentIDD){
        if($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckPlanExist($planID) == 1 && $this->iN_CheckUserExist($userID) == 1){
            DB::exec("UPDATE i_bank_payments SET request_status = 'yes' WHERE iuid_fk = ? AND screen_photo = ?", [(int)$payerID, (int)$imageID]);
            DB::exec("UPDATE i_user_payments SET payment_status = 'ok' WHERE payment_id = ? AND payer_iuid_fk = ? AND bank_payment_image = ?", [(int)$paymentIDD, (int)$payerID, (int)$imageID]);
            $planData = $this->GetPlanDetails($planID);
            $planCreditAmount = isset($planData['plan_amount']) ? $planData['plan_amount'] : NULL;
            if($planCreditAmount){
                DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [(string)$planCreditAmount, (int)$payerID]);
            }
            return true;
        }
    }
	/*Delete PAYMENT*/
    public function iN_DeclineBankPaymentRequest($userID, $payerID, $planID, $imageID,$paymentIDD){
        if($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckPlanExist($planID) == 1 && $this->iN_CheckUserExist($userID) == 1){
            DB::exec("DELETE FROM i_user_payments WHERE payment_id = ?", [(int)$paymentIDD]);
            DB::exec("DELETE FROM i_bank_payments WHERE iuid_fk = ? AND screen_photo = ?", [(int)$payerID, (int)$imageID]);
            DB::exec("DELETE FROM i_user_uploads WHERE iuid_fk = ? AND upload_id = ?", [(int)$payerID, (int)$imageID]);
            return true;
        }
    }
	/*User Total Point Payments*/
    public function iN_UserTotalTransactionsPoints($userID) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT COUNT(*) FROM i_user_payments WHERE payment_type IN('point')");
            return $val !== false ? $val : '0';
        }
    }
	/*Latest 5 Subscriptions List*/
public function iN_TransactionsListPoints($userID, $paginationLimit, $page) {
        $start_from = ((int)$page - 1) * (int)$paginationLimit;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $limit = (int)$paginationLimit;
            $sql = "SELECT P.*, U.* FROM i_user_payments P INNER JOIN i_users U ON P.payer_iuid_fk = U.iuid WHERE U.uStatus IN('1','3') AND P.payment_type = 'point' ORDER BY P.payment_id DESC LIMIT $start_from, $limit";
            $rows = DB::all($sql);
            return !empty($rows) ? $rows : null;
        }
    }
	/*Update Auto Detect Language Mod*/
		public function iN_UpdateDetectLanguageStatus($userID, $mod) {
			if ($this->iN_CheckIsAdmin($userID) == 1) {
				DB::exec("UPDATE i_configurations SET auto_detect_language_status = ? WHERE configuration_id = 1", [(string)$mod]);
				return true;
			} else {
				return false;
			}
		}
	/*Update Email Send Mod*/
	public function iN_UpdateEmailSendStatusForCPP($userID, $mod) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("UPDATE i_configurations SET send__email = ? WHERE configuration_id = 1", [(string)$mod]);
			return true;
		} else {
			return false;
		}
	}
	/*Update Bank Payment Status*/
	public function iN_UpdateBankPaymentPagoStatus($userID, $mode) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("UPDATE i_payment_methods SET bank_payment_status = ? WHERE payment_method_id = 1", [(string)$mode]);
			return true;
		} else {
			return false;
		}
	}
	/*Update Bank Payment Details*/
	public function iN_UpdateBankPaymentDetails($userID, $percentageFee, $fixedCharge, $bankDescription) {
		if ($this->iN_CheckIsAdmin($userID) == 1) {
			DB::exec("UPDATE i_payment_methods SET bank_payment_percentage_fee = ?, bank_payment_fixed_charge = ?, bank_payment_details = ? WHERE payment_method_id = 1",
				[(string)$percentageFee, (string)$fixedCharge, (string)$bankDescription]
			);
			return true;
		} else {
			return false;
		}
	}
	/*Premium Frame Gif Plan List*/
    public function iN_FrameListFromAdmin() {
        $rows = DB::all("SELECT * FROM i_frames");
        return !empty($rows) ? $rows : null;
    }
	/*Premium Frame Gif Plan List*/
    public function iN_FrameListFromProfile() {
        $rows = DB::all("SELECT * FROM i_frames WHERE f_frame_status = '1'");
        return !empty($rows) ? $rows : null;
    }
	/*Update Frame Plan*/
    public function iN_UpdateFramePlanFromID($userID, $frameAmount, $frameFile,$frameID) {
        if ($this->iN_CheckIsAdmin($userID) == 1 && $this->CheckFramePlanExist($frameID)) {
            DB::exec("UPDATE i_frames SET f_price = ?, f_file = ? WHERE f_id = ?", [(string)$frameAmount, (string)$frameFile, (int)$frameID]);
            return true;
        } else {
            return false;
        }
    }
    public function iN_CheckFramePurchased($userID, $frameID){
        return (bool) DB::col("SELECT 1 FROM i_user_frames WHERE f_purchased_frame_id = ? LIMIT 1", [(int)$frameID]);
    }
	/*PurchaseFrame*/
    public function iN_PurchaseFrame($userID, $purchasedID, $frameID, $onePointEqual){
        $purchaseTime = time();
        $frameData = $this->GetFramePlanDetails($frameID);
        $frameImagePath = isset($frameData['f_file']) ? $frameData['f_file'] : NULL;
        $framePrice = isset($frameData['f_price']) ? $frameData['f_price'] : '0';
        $framePrice = str_replace(',', '.', $framePrice);
        $onePointEqual = str_replace(',', '.', $onePointEqual);
        $framePrice = floatval($framePrice);
        $frameAmount = floatval($framePrice) * floatval($onePointEqual);

        if ($this->iN_CheckuserExist($userID) == '1' && $this->iN_CheckuserExist($purchasedID) == '1') {
            try {
                DB::begin();
                DB::exec("UPDATE i_users SET user_frame = ? WHERE iuid = ?", [(string)$frameImagePath, (int)$purchasedID]);
                DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [(int)$framePrice, (int)$userID]);
                if ($this->iN_CheckFramePurchased($userID, $frameID) == 1) {
                    DB::exec("UPDATE i_user_frames SET f_total_purchased_this_frame = f_total_purchased_this_frame + 1 WHERE f_purchased_frame_id = ?", [(int)$frameID]);
                } else {
                    DB::exec("INSERT INTO i_user_frames (f_puchased_user_id, f_purchaser_user_id, f_purchased_time, f_purchase_price, f_purchased_frame_id, f_total_purchased_this_frame) VALUES (?,?,?,?,?, '1')",
                        [(int)$purchasedID, (int)$userID, $purchaseTime, (string)$framePrice, (int)$frameID]
                    );
                }
                DB::exec("INSERT INTO i_user_payments (payer_iuid_fk, payed_iuid_fk, purchased_frame_id, payment_type, payment_time, payment_status, amount, admin_earning) VALUES (?,?,?,'frame',?,'ok',?,?)",
                    [(int)$userID, (int)$purchasedID, (int)$frameID, $purchaseTime, (string)$frameAmount, (string)$frameAmount]
                );
                DB::commit();
                return true;
            } catch (Throwable $e) { DB::rollBack(); return false; }
        }

        return false;
    }
	/*Premium Frame Gif Plan List*/
    public function iN_FrameListFromUserDashboard() {
        $rows = DB::all("SELECT * FROM i_frames WHERE f_frame_status = '1'");
        return !empty($rows) ? $rows : null;
    }
    public function iN_CheckUserHasThisFrame($userID, $frameID){
        if($this->CheckFramePlanExist($frameID) == '1' && $this->iN_CheckuserExist($userID) == '1'){
            $exists = (bool) DB::col("SELECT 1 FROM i_user_frames WHERE f_puchased_user_id = ? AND f_purchased_frame_id = ? LIMIT 1", [(int)$userID, (int)$frameID]);
            return $exists ? true : false;
        }
    }

    public function iN_FrameListWithCheckFromUserDashboard($userID) {
        $sql = "SELECT i_frames.*, i_user_frames.f_purchased_frame_id IS NOT NULL AS purchased
                FROM i_frames LEFT JOIN i_user_frames
                  ON i_frames.f_id = i_user_frames.f_purchased_frame_id AND i_user_frames.f_puchased_user_id = ?
                WHERE i_frames.f_frame_status = '1'";
        $rows = DB::all($sql, [(int)$userID]);
        return !empty($rows) ? $rows : null;
    }
    /*Check UnSubscribe Status*/
    public function iN_CheckUnsubscribeStatus($userID, $profileID){
        if($this->iN_CheckuserExist($userID) == '1' && $this->iN_CheckuserExist($profileID) == '1'){
            $row = DB::one("SELECT plan_period_end FROM i_user_subscriptions WHERE iuid_fk = ? AND subscribed_iuid_fk = ? AND status = 'inactive' AND in_status = '1' AND finished = '1' AND UNIX_TIMESTAMP(plan_period_end) > UNIX_TIMESTAMP(NOW()) ORDER BY subscription_id DESC LIMIT 1",
                [(int)$userID, (int)$profileID]
            );
            return $row ? ($row['plan_period_end'] ?? false) : false;
        }
    }
    /*Update Frame*/
    public function iN_UpdateFrame($userID, $frameID){
        if($this->CheckFramePlanExist($frameID) == '1' && $this->iN_CheckuserExist($userID) == '1' && $this->iN_CheckUserHasThisFrame($userID, $frameID)){
            $frameData = $this->GetFramePlanDetails($frameID);
            $frameImagePath = isset($frameData['f_file']) ? $frameData['f_file'] : NULL;
            DB::exec("UPDATE i_users SET user_frame = ? WHERE iuid = ?", [(string)$frameImagePath, (int)$userID]);
            return true;
        }
    }
    /*Calculate All Questions*/
    public function iN_CalculateAllUnreadQuestions() {
        $val = DB::col("SELECT COUNT(*) FROM i_contacts WHERE contact_read_status = '0'");
        return $val !== false ? $val : '0';
    }
	/*Set Blur*/
    public function iN_CheckMuteBlurOwner($userID,$chatIDfk){
        $row = DB::one("SELECT video_muted FROM i_video_call WHERE video_muted = ? AND chat_id_fk = ?", [(int)$userID,(int)$chatIDfk]);
        return $row ? ($row['video_muted'] ?? false) : false;

	}
	/*Mute Owner*/
    public function iN_CheckMuteOwner($userID,$chatIDfk){
        return (bool) DB::col("SELECT 1 FROM i_video_call WHERE video_muted = ? AND chat_id_fk = ? LIMIT 1", [(int)$userID,(int)$chatIDfk]);
    }
	/*Update Video Mute UnMute*/
    public function iN_UpdateMuteUnmute($userID,$chatIDfk){
        if ($this->iN_CheckUserExist($userID) == 1) {
            if($this->iN_VideoBlurCheck($chatIDfk) == 'not'){
                DB::exec("UPDATE i_video_call SET video_muted = ? WHERE chat_id_fk = ?", [(int)$userID,(int)$chatIDfk]);
            }else{
                if($this->iN_CheckMuteOwner($userID,$chatIDfk) == '1'){
                    DB::exec("UPDATE i_video_call SET video_muted = NULL WHERE chat_id_fk = ?", [(int)$chatIDfk]);
                }
            }
            return true;
        }

    }
	/*Check Video Blured*/
    public function iN_VideoBlurCheck($chatIDfk){
        $val = DB::col("SELECT 1 FROM i_video_call WHERE chat_id_fk = ? AND video_muted IS NOT NULL LIMIT 1", [(int)$chatIDfk]);
        return $val ? 'ok' : 'not';
    }
	/*Check row exist in table*/
    public function iN_CheckRowExist($dataRow){
        if (!$dataRow) { return false; }
        $exists = DB::col(
            "SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'i_configurations' AND COLUMN_NAME = ? LIMIT 1",
            [(string)$dataRow]
        );
        return $exists ? true : false;
    }
	/*Save Color Changes*/
    public function iN_ChangeColor($userID, $dataRow, $color){
        if($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckRowExist($dataRow) == '1'){
            DB::exec("UPDATE i_configurations SET $dataRow = ? WHERE configuration_id = 1", [(string)$color]);
            return true;
        }else{
            return false;
        }
    }
	/*Save Color Changes*/
    public function iN_UpdateDefaultColor($userID, $dataRow){
        if($this->iN_CheckIsAdmin($userID) == 1 && $this->iN_CheckRowExist($dataRow) == '1'){
            DB::exec("UPDATE i_configurations SET $dataRow = NULL WHERE configuration_id = 1");
            return true;
        }else{
            return false;
        }
    }
	/*Current Total Onlien Users*/
    public function getTotalCurrentOnlineUsers() {
        $val = DB::col("SELECT COUNT(*) FROM i_users WHERE last_login_time >= UNIX_TIMESTAMP(NOW() - INTERVAL 1 MINUTE)");
        return $val !== false ? (string)$val : '0';
    }
    public function iN_GetTotalHotPosts($uid, $showingPost, $showingTrendPostLimitDay) {
        $showingPosts = (int)$showingPost;
        $days = (int)$showingTrendPostLimitDay;
        $sql = "SELECT p.*, u.*, IFNULL(SUM(c.comment_count), 0) AS total_comments, IFNULL(SUM(l.like_count), 0) AS total_likes
            FROM i_posts p
            LEFT JOIN i_users u ON p.post_owner_id = u.iuid
            LEFT JOIN (
                SELECT ipc.comment_post_id_fk, COUNT(ipc.com_id) AS comment_count
                FROM i_post_comments ipc
                GROUP BY ipc.comment_post_id_fk
            ) c ON p.post_id = c.comment_post_id_fk
            LEFT JOIN (
                SELECT ipl.post_id_fk, COUNT(ipl.like_id) AS like_count
                FROM i_post_likes ipl
                GROUP BY ipl.post_id_fk
            ) l ON p.post_id = l.post_id_fk
            WHERE p.post_created_time >= UNIX_TIMESTAMP(NOW() - INTERVAL $days DAY)
              AND p.post_type NOT IN('reels')
            GROUP BY p.post_id
            HAVING IFNULL(SUM(c.comment_count), 0) + IFNULL(SUM(l.like_count), 0) > 0
            ORDER BY (IFNULL(SUM(c.comment_count), 0) + IFNULL(SUM(l.like_count), 0)) DESC, p.post_created_time DESC
            LIMIT $showingPosts";
        $rows = DB::all($sql);
        return !empty($rows) ? $rows : null;
    }

   public function iN_FriendsActivity($uid , $showNumber, $avtivityTime) {
        $uid = (int)$uid; $showNumber = (int)$showNumber; $avtivityTime = (int)$avtivityTime;
        $sql = "SELECT DISTINCT A.*, U.*
            FROM i_friends F
            INNER JOIN i_user_activity A ON A.uid_fk = F.fr_two
            INNER JOIN i_users U ON A.uid_fk = U.iuid
            WHERE F.fr_one = ?
              AND U.uStatus IN ('1', '3')
              AND F.fr_status IN ('flwr', 'subscriber')
              AND A.activity_time > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL $avtivityTime DAY))
            ORDER BY A.activity_id DESC
            LIMIT $showNumber";
        $rows = DB::all($sql, [$uid]);
        return !empty($rows) ? $rows : [];
    }

    public function iN_InsertPostActivity($userID, $activityType,$postID, $time){
        DB::exec("INSERT INTO i_user_activity(uid_fk, post_id, activity_type, activity_time) VALUES(?, ?, ?, ?)",
            [(int)$userID,(int)$postID,(string)$activityType,(int)$time]
        );
        return true;
    }
    public function iN_InsertPostLikeActivity($userID, $activityType,$postID, $time){
        DB::exec("INSERT INTO i_user_activity(uid_fk, post_id, activity_type, activity_time) VALUES(?, ?, ?, ?)",
            [(int)$userID,(int)$postID,(string)$activityType,(int)$time]
        );
        return true;
    }
    public function iN_InsertFollowActivity($userID, $activityType,$followedUserID, $time){
        DB::exec("INSERT INTO i_user_activity(uid_fk, fr_id, activity_type, activity_time) VALUES(?, ?, ?, ?)",
            [(int)$userID,(int)$followedUserID,(string)$activityType,(int)$time]
        );
        return true;
    }
    public function iN_DeletePostActivity($userID, $postID){
        DB::exec("DELETE FROM i_user_activity WHERE post_id = ? AND uid_fk = ? AND activity_type = 'newPost'", [(int)$postID,(int)$userID]);
        return true;
    }
    public function iN_DeletePostLikeActivity($userID, $postID){
        DB::exec("DELETE FROM i_user_activity WHERE post_id = ? AND uid_fk = ? AND activity_type = 'postLike'", [(int)$postID,(int)$userID]);
        return true;
    }
    public function iN_DeleteFollowActivity($userID, $frID){
        DB::exec("DELETE FROM i_user_activity WHERE fr_id = ? AND uid_fk = ? AND activity_type = 'userFollow'", [(int)$frID,(int)$userID]);
        return true;
    }

    public function iN_UpdateSubscriptionType($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET unsubscribe_style = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }

    public function iN_UpdateAutoFollowAdminStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET auto_follow_admin = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        } else {
            return false;
        }
    }
	/*Update Payment Success Status*/
    public function iN_UpdatePaymentSuccessStatusAmount($userID, $paymentID, $amount){
        if ($this->iN_CheckUserExist($userID) == '1') {
            DB::exec("UPDATE i_user_payments SET payment_status = 'pending', amount = ? WHERE payment_id = ?", [(string)$amount, (int)$paymentID]);
            return true;
        }
    }
	/*Total Admin Premium Earnings*/
    public function iN_TotalPremiumEarningsNetPoint($userID) {
        $userID = (int)$userID;
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            $val = DB::col("SELECT SUM(amount) FROM i_user_payments WHERE payment_status = 'ok' AND credit_plan_id IS NOT NULL");
            return $val ? (string)$val : '0.00';
        }
    }
	/*Ai Used and Decrase Credit*/
    public function iN_AiUsed($userID, $credit){
        if ($this->iN_CheckUserExist($userID) == '1') {
            $row = DB::one("SELECT wallet_points FROM i_users WHERE iuid = ?", [(int)$userID]);
            $wallet = $row ? (int)$row['wallet_points'] : 0;
            $credit = (int)$credit;
            if ($wallet >= $credit) {
                DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [$credit, (int)$userID]);
                return true;
            }
        }
        return false;
    }

    public function iN_UpdateAiGeneratorStatus($userID, $mod) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET open_ai_status = ? WHERE configuration_id = 1", [(string)$mod]);
            return true;
        }
        return false;
    }
    public function iN_UpdateAiAPIData($userID, $aiKey, $aiPerAmount) {
        if ($this->iN_CheckIsAdmin($userID) == 1) {
            DB::exec("UPDATE i_configurations SET openai_api_key = ?, per_ai_use_credit = ? WHERE configuration_id = 1", [(string)$aiKey,(string)$aiPerAmount]);
            return true;
        } else {
            return false;
        }
    }
    /*INSERT UPLOADED FILES FROM UPLOADS TABLE*/
    public function iN_INSERTUploadedReelFile($uid, $filePath, $tumbnailPath, $fileXPath, $ext) {
        $uploadTime = time();
        $userIP = $_SERVER['REMOTE_ADDR'] ?? '';

        // Pick a safe upload_type that exists in this database schema
        $desiredType = 'reels';
        $fallbackType = 'wall';
        $effectiveType = $desiredType;

        try {
            // Detect if the enum for upload_type contains 'reels'
            $col = DB::one("SHOW COLUMNS FROM i_user_uploads LIKE 'upload_type'");
            if ($col && isset($col['Type'])) {
                $typeDef = (string)$col['Type']; // e.g. enum('wall','profile','product',...)
                if (strpos($typeDef, "'{$desiredType}'") === false) {
                    $effectiveType = $fallbackType;
                }
            }
        } catch (Throwable $e) {
            // If schema introspection fails, attempt with desired type; we'll fallback on insert error below
            $effectiveType = $desiredType;
        }

        // Try insert with detected type; fallback to 'wall' if enum rejects it
        try {
            DB::exec(
                "INSERT INTO i_user_uploads (iuid_fk, uploaded_file_path, upload_tumbnail_file_path, uploaded_x_file_path, uploaded_file_ext, upload_time, ip, upload_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [(int)$uid, (string)$filePath, (string)$tumbnailPath, (string)$fileXPath, (string)$ext, $uploadTime, (string)$userIP, (string)$effectiveType]
            );
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            $enumErr = (stripos($msg, 'Data truncated for column') !== false) || (stripos($msg, 'Incorrect enum value') !== false);
            if ($enumErr && $effectiveType !== $fallbackType) {
                // Retry once with fallback value that should exist on older schemas
                DB::exec(
                    "INSERT INTO i_user_uploads (iuid_fk, uploaded_file_path, upload_tumbnail_file_path, uploaded_x_file_path, uploaded_file_ext, upload_time, ip, upload_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    [(int)$uid, (string)$filePath, (string)$tumbnailPath, (string)$fileXPath, (string)$ext, $uploadTime, (string)$userIP, (string)$fallbackType]
                );
            } else {
                throw $e;
            }
        }

        return (int) DB::lastId();
    }
    public function iN_GetReelsVideoDetailsByID($userID, $fileID) {
        if ($fileID) {
            return DB::one("SELECT upload_id, uploaded_file_path, upload_tumbnail_file_path, upload_type, uploaded_file_ext, upload_time FROM i_user_uploads WHERE iuid_fk = ? AND upload_id = ?", [(int)$userID, (int)$fileID]);
        } else {
            return false;
        }
    }
    public function iN_CheckUploadStatus($fileID){
        $exists = (bool) DB::col("SELECT 1 FROM i_user_uploads WHERE upload_id = ? AND upload_status = '0' LIMIT 1", [(int)$fileID]);
        return $exists ? true : false;
    }
	/*INSERT NEW POST AND GET REAL TIME*/
public function iN_InsertNewReelsPost($uid, $postText, $urlSlug, $postFiles, $postWhoCanSee, $hashTags, $pointAmount,$autoApprovePostStatus) {
        $time = time();
        $userIP = $_SERVER['REMOTE_ADDR'] ?? '';
        $postStatus = ($postWhoCanSee == '4' && $autoApprovePostStatus == 'no') ? '2' : '1';
        // Ensure post_type is valid for this schema ('reels' may not exist on old DBs)
        $postType = 'reels';
        try {
            $col = DB::one("SHOW COLUMNS FROM i_posts LIKE 'post_type'");
            if ($col && isset($col['Type'])) {
                $typeDef = (string)$col['Type'];
                if (strpos($typeDef, "'reels'") === false) { $postType = 'normal'; }
            }
        } catch (Throwable $e) {
            // leave default 'reels'
        }
        DB::exec(
            "INSERT INTO i_posts (post_owner_id,post_text,post_file,post_created_time,post_creator_ip,who_can_see,post_status,url_slug,hashtags,post_wanted_credit,post_type) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
            [(int)$uid,(string)$postText,(string)$postFiles,$time,(string)$userIP,(string)$postWhoCanSee,(string)$postStatus,(string)$postFiles,(string)$hashTags,(string)$pointAmount,(string)$postType]
        );
        $lastId = (int) DB::lastId();
        $result = DB::one(
            "SELECT P.post_id,P.shared_post_id,P.post_pined,P.post_owner_id,P.post_text,P.hashtags,P.post_file,P.post_created_time,P.who_can_see,P.post_want_status,P.post_wanted_credit,P.url_slug,P.post_status,P.comment_status,U.payout_method,U.iuid,U.i_username,U.i_user_fullname,U.user_avatar,U.user_gender,U.payout_method,U.last_login_time,U.user_verified_status,U.thanks_for_tip
             FROM i_posts P FORCE INDEX(ixForcePostOwner)
             INNER JOIN i_users U FORCE INDEX(ixForceUser) ON P.post_owner_id = U.iuid
             WHERE P.post_id = ? LIMIT 1",
            [$lastId]
        );
        $newPostID = $result['post_id'] ?? null;
        if($newPostID){
            $this->iN_InsertPostActivity($uid, 'newPost',$newPostID, $time);
            $this->iN_UpdateUploadStatus($postFiles);
        }
        return $result;
    }
        public function iN_GetInitialReels(?int $startFrom = null, int $limit = 2, int $currentUserId = 0): array {
            $reels = [];
            $currentUserId = (int)$currentUserId;

        if ($startFrom !== null) {
            $startFrom = (int) $startFrom;
            $limit = (int) $limit;

            $query = "
                SELECT
                    P.post_id,
                    P.post_file,
                    P.post_text,
                    P.post_owner_id,
                    P.who_can_see,
                    P.post_wanted_credit,
                    U.i_username,
                    U.i_user_fullname,
                    U.user_avatar,
                    CASE
                        WHEN P.post_id = {$startFrom} THEN 0
                        ELSE 1
                    END AS sort_order
                FROM i_posts P
                INNER JOIN i_users U ON P.post_owner_id = U.iuid
                WHERE P.post_type = 'reels'
                  AND (P.post_status = '1' OR P.post_owner_id = {$currentUserId})
                ORDER BY sort_order ASC, P.post_id DESC
                LIMIT {$limit}
            ";
        } else {
            $limit = (int) $limit;

            $query = "
                SELECT
                    P.post_id,
                    P.post_file,
                    P.post_text,
                    P.post_owner_id,
                    P.who_can_see,
                    P.post_wanted_credit,
                    U.i_username,
                    U.i_user_fullname,
                    U.user_avatar
                FROM i_posts P
                INNER JOIN i_users U ON P.post_owner_id = U.iuid
                WHERE P.post_type = 'reels'
                  AND (P.post_status = '1' OR P.post_owner_id = {$currentUserId})
                ORDER BY P.post_id DESC
                LIMIT {$limit}
            ";
        }

        // Use PDO via DB helper instead of mysqli
        $rows = DB::all($query);
        if (!empty($rows)) {
            $reels = $rows;
        }
        return $reels;
    }
}
?>
