function makeAjaxRequest(
  url,
  method,
  data = {},
  successCallback,
  errorCallback
) {
  let isFormData = data instanceof FormData;
  $.ajax({
    url: url,
    type: method,
    data: data,
    processData: !isFormData, // Important for FormData
    contentType: isFormData
      ? false
      : "application/x-www-form-urlencoded; charset=UTF-8",

    success: function (response) {
      let jsonResponse;
      try {
        // Try to parse the response as JSON
        jsonResponse = JSON.parse(response);
      } catch (e) {
        // If parsing fails, handle it as a plain string or other type
        jsonResponse = null;
      }

      if (jsonResponse && typeof successCallback === "function") {
        successCallback(jsonResponse);
      } else {
        // Handle non-JSON response
        successCallback(response);
      }
    },
    error: function (xhr, status, error) {
      if (typeof errorCallback === "function") {
        errorCallback(xhr, status, error);
      }
    },
  });
}
