/**
 * OneSignal Push Notification Handler
 * 
 * This script initializes OneSignal SDK and synchronizes the user's device key
 * with the backend for web push notifications.
 * 
 * Required dynamic values are passed through window variables:
 * - window.onesignal_app_id         => OneSignal App ID
 * - window.onesignal_user_id       => Current user's device key
 * - window.onesignal_request_url   => URL for saving/removing device key
 */

(function () {
  "use strict";

  // Get dynamic values from window (injected via PHP)
  const appId = window.onesignal_app_id || "";
  const myUserId = window.onesignal_user_id || "";
  const requestURL = window.onesignal_request_url || "";
  let push_user_id = "";

  // Initialize OneSignal SDK
  const OneSignal = window.OneSignal || [];
  OneSignal.push(["init", {
    appId: appId,
    autoResubscribe: true,
    notifyButton: { enable: true },
    persistNotification: true
  }]);

  // Once initialized, retrieve userId and send it to the backend
  OneSignal.push(function () {
    OneSignal.getUserId(function (userId) {
      push_user_id = userId;

      // Only send device key if not already saved
      if (userId !== myUserId) {
        $.get(requestURL, { f: "device_key", id: push_user_id });
      }
    });

    // Listen for subscription status changes
    OneSignal.on("subscriptionChange", function (isSubscribed) {
      const urlParams = isSubscribed
        ? { f: "device_key", id: push_user_id }
        : { f: "remove_device_key" };

      // Sync changes with backend
      $.get(requestURL, urlParams);
    });
  });
})();