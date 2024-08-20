let house_design_id = null; // Global variable to track the current house design ID

jQuery(document).ready(function ($) {
  // Function to load house designs
  function loadHouseDesigns() {
    $.ajax({
      url: ajaxurl, // WordPress provides this global variable
      method: "POST",
      data: {
        action: "house_design_all", // Matches the action in the PHP
      },
      success: function (response) {
        // Check if the response is successful and contains data
        if (response.success && response.data.length > 0) {
          // Container for house designs
          const houseContainer = $("#house-designs");
          houseContainer.empty(); // Clear the container before adding new items

          response.data.forEach((houseDesign) => {
            // Calculate the number of design variations and plans
            const numDesignVariations = houseDesign.design_variations
              ? houseDesign.design_variations.length
              : 0;
            const numPlans = houseDesign.house_plans
              ? houseDesign.house_plans.length
              : 0;

            let houseDesignItem;

            if (numDesignVariations > 0 && numPlans > 0) {
              // Full view with carousel and plan details if both plans and variations exist
              houseDesignItem = `
                <div class="house-design-item mb-3">
                  <div class="col-12 row border border-dark">
                    <div class="col-12 text-center py-3 row justify-content-between">
                      <div class="col-12 col-md-8 col-lg-3 row align-items-center mb-3 mb-lg-0">
                        <h4 class="d-inline house-design-title">${
                          houseDesign.title
                        }</h4>
                      </div>
                      <div class="col-12 col-md-4 col-lg-3 row align-items-center mb-3 mb-lg-0 order-lg-3">
                        <div>
                          <button class="btn btn-outline-dark w-25 mx-1 delete-house-design-button"
                            data-house-design-id="${houseDesign.id}">
                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                          </button>
                          <button class="btn btn-outline-dark w-25 mx-1 edit-house-design-button"
                            data-house-design-id="${houseDesign.id}">
                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                          </button>
                        </div>
                      </div>
                      <div class="col border-dark py-2 px-3">
                        <div class="col row align-items-center">
                          <div>
                            <h6 class="d-inline">Floor Sizes :</h6>
                            <span class="d-inline plan-buttons">
                              ${houseDesign.house_plans
                                .map(
                                  (plan, index) => `
                                  <button class="btn btn-dark my-1 house-plan-button"
                                    data-blueprint="${
                                      plan.blueprint_image_url
                                    }" 
                                    id="${plan.id}"
                                    data-price="${plan.price}"
                                    data-description="${plan.description}" 
                                    data-bedrooms="${plan.bedrooms}"
                                    data-bathrooms="${plan.bathrooms}" 
                                    data-living_rooms="${plan.living_rooms}"
                                    data-carport="${plan.carport}" 
                                    data-area="${plan.area}" 
                                    data-length="${plan.length}"
                                    data-width="${plan.width}" 
                                    ${index === 0 ? 'data-default="true"' : ""}>
                                    Plan ${plan.id}
                                  </button>`
                                )
                                .join("")}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 row border-bottom border-end border-start border-dark">
                    <div class="container container-fluid mx-0 px-0">
                      <div class="row">
                        <!-- Design Variation View -->
                        <div class="col-12 col-md-6 col-lg-4 row justify-content-around py-2 mb-3 mb-lg-0">
                          <div id="carouselExampleDark-${houseDesign.id}"
                            class="carousel carousel-dark slide d-flex justify-content-center align-items-center">
                            <div class="carousel-indicators">
                              ${houseDesign.design_variations
                                .map(
                                  (variation, index) => `
                                  <button type="button" data-bs-target="#carouselExampleDark-${
                                    houseDesign.id
                                  }"
                                    data-bs-slide-to="${index}" class="${
                                    index === 0 ? "active" : ""
                                  }"
                                    aria-current="${
                                      index === 0 ? "true" : "false"
                                    }" aria-label="Slide ${index + 1}">
                                  </button>`
                                )
                                .join("")}
                            </div>
                            <div class="carousel-inner">
                              ${houseDesign.design_variations
                                .map(
                                  (variation, index) => `
                                  <div class="carousel-item ${
                                    index === 0 ? "active" : ""
                                  }" data-bs-interval="10000">
                                    <img src="${
                                      variation.image_url
                                    }" class="d-block w-100" alt="${
                                    variation.title
                                  }">
                                    <h5 class="text-center mx-2">${
                                      variation.title
                                    }</h5>
                                  </div>`
                                )
                                .join("")}
                            </div>
                            <button class="carousel-control-prev" type="button"
                              data-bs-target="#carouselExampleDark-${
                                houseDesign.id
                              }" data-bs-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                              data-bs-target="#carouselExampleDark-${
                                houseDesign.id
                              }" data-bs-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Next</span>
                            </button>
                          </div>
                        </div>

                        <!-- Plan Blueprint View -->
                        <div class="col-12 col-md-6 col-lg-4 mb-3 mb-lg-0 d-flex justify-content-center align-items-center">
                          <img id="blueprint-image" src="${
                            houseDesign.house_plans[0].blueprint_image_url
                          }"
                            class="d-block w-100" alt="Blueprint Image">
                        </div>
                        <hr class="d-lg-none">

                        <!-- Plan Variation View -->
                        <div class="col-12 col-lg-4 row align-items-center">
                          <div class="col-12 text-center">
                            <span class="d-block">Price: <h4 class="d-inline" id="plan-price">
                                ${houseDesign.house_plans[0].price}</h4></span>
                            <span id="plan-description">${
                              houseDesign.house_plans[0].description
                            }</span>
                          </div>
                          <hr class="mt-3">
                          <div class="mb-3 col-9 col-md-12 text-center row justify-content-around px-2 py-2 align-items-center">
                            <div class="col">
                              <h6 class="d-inline"><i class="fa-solid fa-bed"></i>: <span id="plan-bedrooms">${
                                houseDesign.house_plans[0].bedrooms
                              }</span></h6>
                            </div>
                            <div class="col">
                              <h6 class="d-inline"><i class="fa-solid fa-couch"></i>: <span id="plan-living_rooms">${
                                houseDesign.house_plans[0].living_rooms
                              }</span></h6>
                            </div>
                            <div class="col">
                              <h6 class="d-inline"><i class="fa-solid fa-bath"></i>: <span id="plan-bathrooms">${
                                houseDesign.house_plans[0].bathrooms
                              }</span></h6>
                            </div>
                            <div class="col">
                              <h6 class="d-inline"><i class="fa-solid fa-car"></i>: <span id="plan-carport">${
                                houseDesign.house_plans[0].carport
                              }</span></h6>
                            </div>
                          </div>
                          <hr class="mt-2">
                          <div class="col-12">
                            <h5 class="text-center">House Dimensions</h5>
                            <div class="row justify-content-between px-5">
                              <div class="col-6 text-start">
                                <h6>House Area</h6>
                              </div>
                              <div class="col-6 text-end">
                                <span id="plan-area">${
                                  houseDesign.house_plans[0].area
                                }</span> sqm
                              </div>
                              <div class="col-6 text-start">
                                <h6>House Width</h6>
                              </div>
                              <div class="col-6 text-end">
                                <span id="plan-width">${
                                  houseDesign.house_plans[0].width
                                }</span> sqm
                              </div>
                              <div class="col-6 text-start">
                                <h6>House Length</h6>
                              </div>
                              <div class="col-6 text-end">
                                <span id="plan-length">${
                                  houseDesign.house_plans[0].length
                                }</span> sqm
                              </div>
                            </div>
                          </div>
                          <hr>
                          <div class="col-12 text-center py-3 py-lg-2">
                            <button id="visit-page-button" class="btn btn-dark w-25 mx-2"
                              onclick="window.location.href='${
                                houseDesign.visiting_page_url
                              }'">Visit</button>
                            <button id="quote-page-button" class="btn btn-dark w-25 mx-2"
                              onclick="window.location.href='${
                                houseDesign.quotation_url
                              }'">Quote</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>`;
            } else {
              // Simple view with counts if either plans or variations are missing
              houseDesignItem = `
                <div class="house-design-item mb-3">
                  <div class="col-12 row border border-dark">
                    <div class="col-12 text-center py-3 row justify-content-between">
                      <div class="col-12 col-md-8 col-lg-3 row align-items-center mb-3 mb-lg-0">
                        <h4 class="d-inline house-design-title">${houseDesign.title}</h4>
                      </div>
                      <div class="col-12 col-md-4 col-lg-3 row align-items-center mb-3 mb-lg-0 order-lg-3">
                        <div>
                          <button class="btn btn-outline-dark w-25 mx-1 delete-house-design-button"
                            data-house-design-id="${houseDesign.id}">
                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                          </button>
                          <button class="btn btn-outline-dark w-25 mx-1 edit-house-design-button"
                            data-house-design-id="${houseDesign.id}">
                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                          </button>
                        </div>
                      </div>
                      <div class="col border-dark py-2 px-3">
                        <div class="col row align-items-center">
                          <div class="row">
                            <h6 class="d-inline">Plans : ${numPlans}</h6>
                            <h6 class="d-inline">Variations : ${numDesignVariations}</h6>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>`;
            }

            // Append the house design item to the container
            houseContainer.append(houseDesignItem);

            // Initialize the carousel after appending to the DOM
            if (numDesignVariations > 0 && numPlans > 0) {
              $(`#carouselExampleDark-${houseDesign.id}`).carousel();
            }
          });

          // Reattach click event listeners for the plan buttons
          $(".house-plan-button").on("click", function () {
            const blueprint = $(this).data("blueprint");
            const price = $(this).data("price");
            const description = $(this).data("description");
            const bedrooms = $(this).data("bedrooms");
            const bathrooms = $(this).data("bathrooms");
            const living_rooms = $(this).data("living_rooms");
            const carport = $(this).data("carport");
            const area = $(this).data("area");
            const length = $(this).data("length");
            const width = $(this).data("width");

            // Find the closest parent that is the container for the house design
            const houseDesignContainer = $(this).closest(".house-design-item");

            // Update the plan details view within the same container
            houseDesignContainer
              .find("#blueprint-image")
              .attr("src", blueprint);
            houseDesignContainer.find("#plan-price").text(price);
            houseDesignContainer.find("#plan-description").text(description);
            houseDesignContainer.find("#plan-bedrooms").text(bedrooms);
            houseDesignContainer.find("#plan-bathrooms").text(bathrooms);
            houseDesignContainer.find("#plan-living_rooms").text(living_rooms);
            houseDesignContainer.find("#plan-carport").text(carport);
            houseDesignContainer.find("#plan-area").text(area);
            houseDesignContainer.find("#plan-width").text(width);
            houseDesignContainer.find("#plan-length").text(length);
          });
        } else {
          console.log("No house designs found.");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
        console.error("Status:", status);
        console.error("Response:", xhr.responseText);
      },
    });
  }

  // Initially load house designs
  loadHouseDesigns();

  $(document).on("click", ".edit-house-design-button", function () {
    const houseDesignId = $(this).data("house-design-id");
    house_design_id = houseDesignId; // Update the global ID variable

    $("#landing").addClass("d-none"); // Hide the landing div
    $("#add-design-ui").removeClass("d-none"); // Show the add-design-ui div
    $("#variations-tab").removeClass("d-none"); // Show the variations form container
    $("#plans-tab").removeClass("d-none"); // Show the plans form container

    fetchHouseDesignDetails(houseDesignId); // Fetch and populate the house design details
  });

  function populateHouseDesignDetails(houseDesign) {
    // Populate form fields with the fetched data
    $("#house-design-title").val(houseDesign.title);
    $("#house-design-floors").val(houseDesign.floor_number);
    $("#house-design-landing-page").val(houseDesign.visiting_page_url);
    $("#house-design-quote-page").val(houseDesign.quotation_url);

    // Populate variations and plans
    // Add logic to populate variations and plans as needed
  }

  // Show the Add House Design UI
  $("#add-house-design-button").on("click", function () {
    house_design_id = null; // Clear the global ID variable
    $("#plans").empty(); // Clear the plans container
    $("#variations").empty(); // Clear the variations container
    $("#landing").addClass("d-none"); // Hide the landing div
    $("#add-design-ui").removeClass("d-none"); // Show the add-design-ui div
  });

  // Handle Back to Landing UI
  $("#hide-form").on("click", function (e) {
    loadHouseDesigns();
    $("#variations-tab").addClass("d-none"); // Show the variations form container
    $("#plans-tab").addClass("d-none"); // Show the plans form container
    house_design_id = null;
    e.preventDefault(); // Prevent the default action
    $("#add-design-ui").addClass("d-none"); // Hide the add-design-ui div
    $("#landing").removeClass("d-none"); // Show the landing div
    // Reload the house designs
    $("#house-design-title").val("");
    $("#house-design-floors").val("");
    $("#house-design-landing-page").val("");
    $("#house-design-quote-page").val("");
  });

  // Handle form submission
  $("#house-design-form").on("submit", function (e) {
    e.preventDefault(); // Prevent the form from submitting the traditional way

    // Validate the form (HTML5 validation will ensure fields are filled)
    if (this.checkValidity()) {
      const title = $("#house-design-title").val();
      const floors = $("#house-design-floors").val();
      const landingPage = $("#house-design-landing-page").val();
      const quotePage = $("#house-design-quote-page").val();

      $.ajax({
        url: ajaxurl, // WordPress provides this global variable
        method: "POST",
        data: {
          action: "save_house_design", // Short action name
          id: house_design_id, // Send ID for update, or null for insert
          title: title,
          floors: floors,
          landing_page: landingPage,
          quote_page: quotePage,
        },
        success: function (response) {
          if (response.success) {
            house_design_id = response.data.id; // Set the global ID variable
            alert("House Design saved successfully! ID: " + house_design_id);
            $("#variations-tab").removeClass("d-none");
            $("#plans-tab").removeClass("d-none");
          } else {
            alert("Failed to save House Design.");
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX request failed:", error);
          console.error("Status:", status);
          console.error("Response:", xhr.responseText);
        },
      });
    } else {
      // Trigger HTML5 validation
      this.reportValidity();
    }
  });

  $("#variation-blueprint-input").on("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        $("#add-variation-modal img").attr("src", e.target.result);
      };
      reader.readAsDataURL(file);
    }
  });

  // Update the preview image for house plan
  $("#plan-blueprint-input").on("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        $("#add-plan-modal img").attr("src", e.target.result);
      };
      reader.readAsDataURL(file);
    }
  });

  function fetchHouseDesignDetails(houseDesignId) {
    $("#variations").html(
      '<div class="spinner-border" role="status">' +
        '<span class="visually-hidden">Loading...</span>' +
        "</div>"
    );
    $("#plans").html(
      '<div class="spinner-border" role="status">' +
        '<span class="visually-hidden">Loading...</span>' +
        "</div>"
    );
    $.ajax({
      url: ajaxurl,
      method: "POST",
      data: {
        action: "house_design_get", // Correct action for fetching a single house design
        house_design_id: houseDesignId, // Pass the specific house design ID
      },
      success: function (response) {
        $("#variations").html("");
        $("#plans").html("");
        if (response.success && response.data) {
          const houseDesign = response.data;

          // Clear existing variations and plans

          // Process and populate design variations
          houseDesign.design_variations.forEach((variation) => {
            const variationHtml = `
                        <div class="col-12 card-fix col-md-6 col-lg-4 px-3 d-flex">
                            <div class="card card-fix flex-fill">
                                <div class="card-header text-center">
                                    <h5 class="d-inline">${variation.title}</h5>
                                    <button class="btn btn-dark float-end delete-variation-button" data-id="${variation.id}"><i class="fa-solid fa-trash"></i></button>
                                    <button class="btn btn-dark float-end edit-variation-button" data-id="${variation.id}" data-title="${variation.title}" data-image-url="${variation.image_url}"><i class="fa-solid fa-pen-to-square"></i></button>
                                </div>
                                <div class="card-body">
                                    <img src="${variation.image_url}" class="d-block w-100" alt="${variation.title}">
                                </div>
                            </div>
                        </div>
                    `;
            $("#variations").append(variationHtml);
          });

          // Process and populate house plans
          houseDesign.house_plans.forEach((plan) => {
            const planHtml = `
        <div class="col-12 mb-3 px-3">
            <div class="card card-fix flex-fill">
                <div class="card-header text-center">
                    <h5 class="d-inline">Plan ${plan.id}</h5>
                    <button class="btn btn-dark float-end mx-1 delete-plan-button" data-id="${
                      plan.id
                    }">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <button class="btn btn-dark float-end mx-1 edit-plan-button" 
                        data-id="${plan.id}"
                        data-description="${plan.description}"
                        data-price="${plan.price}"
                        data-bedrooms="${plan.bedrooms}"
                        data-bathrooms="${plan.bathrooms}"
                        data-living_rooms="${plan.living_rooms}"
                        data-carport="${plan.carport}"
                        data-area="${plan.area}"
                        data-length="${plan.length}"
                        data-width="${plan.width}"
                        data-image-url="${plan.blueprint_image_url}"
                        data-butlers-pantry="${plan.butlers_pantry}"
                        data-designer="${plan.designer}"
                        data-dual-living="${plan.dual_living}"
                        data-front-master="${plan.front_master}"
                        data-it-hub="${plan.it_hub}"
                        data-media-room="${plan.media_room}"
                        data-outdoor-living="${plan.outdoor_living}"
                        data-rear-master="${plan.rear_master}"
                        data-study="${plan.study}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="card-body row justify-content-around">
                    <div class="col-12 col-md-6 col-lg-4">
                        <img src="${
                          plan.blueprint_image_url
                        }" class="d-block w-100" alt="Design Image Here">
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 my-3">
                        <div class="text-center">
                            <h6 class="d-inline">Price:
                                <h4 class="d-inline">${plan.price}</h4>
                            </h6>
                        </div>
                        <hr>
                        <div class="row justify-content-between mt-3">
                            <div class="col-6">
                                <div class="input-group m-1">
                                    <span class="input-group-text w-50 justify-content-center">
                                        <i class="fa-solid fa-bed"></i>
                                    </span>
                                    <span class="input-group-text w-50 justify-content-center">
                                        <h6>${plan.bedrooms}</h6>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group m-1">
                                    <span class="input-group-text w-50 justify-content-center">
                                        <i class="fa-solid fa-couch"></i>
                                    </span>
                                    <span class="input-group-text w-50 justify-content-center">
                                        <h6>${plan.living_rooms}</h6>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group m-1">
                                    <span class="input-group-text w-50 justify-content-center">
                                        <i class="fa-solid fa-bath"></i>
                                    </span>
                                    <span class="input-group-text w-50 justify-content-center">
                                        <h6>${plan.bathrooms}</h6>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group m-1">
                                    <span class="input-group-text w-50 justify-content-center">
                                        <i class="fa-solid fa-car"></i>
                                    </span>
                                    <span class="input-group-text w-50 justify-content-center">
                                        <h6>${plan.carport}</h6>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-between mt-3">
                            <div class="col-6 text-start">
                                <h6>House Area</h6>
                            </div>
                            <div class="col-6 text-end">
                                <h5>${plan.area} sqm</h5>
                            </div>
                            <div class="col-6 text-start">
                                <h6>House Width</h6>
                            </div>
                            <div class="col-6 text-end">
                                <h5>${plan.width} sqm</h5>
                            </div>
                            <div class="col-6 text-start">
                                <h6>House Length</h6>
                            </div>
                            <div class="col-6 text-end">
                                <h5>${plan.length} sqm</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 my-3">
                        <div class="text-start">
                            <h6 class="d-inline">Description:
                                <p class="d-inline">${plan.description}</p>
                            </h6>
                        </div>
                        <hr>
                            <div class="row justify-content-around mt-3">
                            <h5 class="text-center">Includes</h5>
                            ${
                              plan.butlers_pantry == 1
                                ? '<div class="px-1 py-0" style="width: max-content;"><div class="card text-center px-1">Butlers Pantry</div></div>'
                                : ""
                            }
                            ${
                              plan.designer == 1
                                ? '<div class="px-1 py-0" style="width: max-content;"><div class="card text-center px-1">Designer</div></div>'
                                : ""
                            }
                            ${
                              plan.dual_living == 1
                                ? '<div class="px-1 py-0" style="width: max-content;"><div class="card text-center px-1">Dual Living</div></div>'
                                : ""
                            }
                            ${
                              plan.front_master == 1
                                ? '<div class="px-1 py-0" style="width: max-content;"><div class="card text-center px-1">Front Master</div></div>'
                                : ""
                            }
                            ${
                              plan.it_hub == 1
                                ? '<div class="px-1 py-0" style="width: max-content;"><div class="card text-center px-1">IT Hub</div></div>'
                                : ""
                            }
                            ${
                              plan.media_room == 1
                                ? '<div class="px-1 py-0" style="width: max-content;"><div class="card text-center px-1">Media Room</div></div>'
                                : ""
                            }
                            ${
                              plan.outdoor_living == 1
                                ? '<div class="px-1 py-0" style="width: max-content;"><div class="card text-center px-1">Outdoor Living</div></div>'
                                : ""
                            }
                            ${
                              plan.rear_master == 1
                                ? '<div class="px-1 py-0" style="width: max-content;"><div class="card text-center px-1">Rear Master</div></div>'
                                : ""
                            }
                            ${
                              plan.study == 1
                                ? '<div class="px-1 py-0" style="width: max-content;"><div class="card text-center px-1">Study</div></div>'
                                : ""
                            }
                            </div>
                        <hr>
                        <div class="row justify-content-around mt-3">
                            <h5 class="text-center">Locations</h5>
                            <!-- Dynamically add location elements here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
            $("#plans").append(planHtml);
          });

          // Populate house design details into the form fields
          populateHouseDesignDetails(houseDesign);
        } else {
          console.log("No house design details found.");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
        console.error("Status:", status);
        console.error("Response:", xhr.responseText);
      },
    });
  }

  // Delete variation button click handler
  $(document).on("click", ".delete-variation-button", function () {
    const variationId = $(this).data("id");
    if (confirm("Are you sure you want to delete this variation?")) {
      deleteVariation(variationId);
    }
  });

  // Delete plan button click handler
  $(document).on("click", ".delete-plan-button", function () {
    const planId = $(this).data("id");
    if (confirm("Are you sure you want to delete this plan?")) {
      deletePlan(planId);
    }
  });

  // Edit variation button click handler
  $(document).on("click", ".edit-variation-button", function () {
    const variationId = $(this).data("id");
    const variationTitle = $(this).data("title");
    const variationImageUrl = $(this).data("image-url");

    // Populate the modal with existing data
    $("#variation-id").val(variationId); // Hidden input to store variation ID
    $("#variation-name-input").val(variationTitle);

    // Show image preview in the modal
    $("#variation-image-preview").attr("src", variationImageUrl);

    // Show the modal for editing
    $("#add-variation-modal").modal("show");
  });

  // Edit plan button click handler
  $(document).on("click", ".edit-plan-button", function () {
    const planId = $(this).data("id");
    $("#plan-id").val(planId);
    const planDescription = $(this).data("description");
    const planPrice = $(this).data("price");
    const planBedrooms = $(this).data("bedrooms");
    const planBathrooms = $(this).data("bathrooms");
    const planLivingRooms = $(this).data("living_rooms");
    const planCarport = $(this).data("carport");
    const planArea = $(this).data("area");
    const planLength = $(this).data("length");
    const planWidth = $(this).data("width");
    const planImageUrl = $(this).data("image-url");

    // Boolean fields (checkboxes)
    const butlersPantry = $(this).data("butlers-pantry");
    const designer = $(this).data("designer");
    const dualLiving = $(this).data("dual-living");
    const frontMaster = $(this).data("front-master");
    const itHub = $(this).data("it-hub");
    const mediaRoom = $(this).data("media-room");
    const outdoorLiving = $(this).data("outdoor-living");
    const rearMaster = $(this).data("rear-master");
    const study = $(this).data("study");

    // Populate the modal with existing data
    $("#plan-id").val(planId); // Hidden input to store plan ID
    $("#plan-description-input").val(planDescription);
    $("#plan-price-input").val(planPrice);
    $("#plan-bedrooms-input").val(planBedrooms);
    $("#plan-bathrooms-input").val(planBathrooms);
    $("#plan-living-rooms-input").val(planLivingRooms);
    $("#plan-carport-input").val(planCarport);
    $("#plan-area-input").val(planArea);
    $("#plan-length-input").val(planLength);
    $("#plan-width-input").val(planWidth);
    $("#plan-image-preview").attr("src", planImageUrl);

    // Populate checkboxes
    $("#plan-butlers-pantry-input").prop("checked", butlersPantry);
    $("#plan-designer-input").prop("checked", designer);
    $("#plan-dual-living-input").prop("checked", dualLiving);
    $("#plan-front-master-input").prop("checked", frontMaster);
    $("#plan-it-hub-input").prop("checked", itHub);
    $("#plan-media-room-input").prop("checked", mediaRoom);
    $("#plan-outdoor-living-input").prop("checked", outdoorLiving);
    $("#plan-rear-master-input").prop("checked", rearMaster);
    $("#plan-study-input").prop("checked", study);

    // Show the modal for editing
    $("#add-plan-modal").modal("show");
  });

  // Save variation
  $("#save-variation-button").on("click", function () {
    const variationId = $("#variation-id").val(); // Hidden input for variation ID
    const formData = new FormData();
    formData.append("title", $("#variation-name-input").val());
    formData.append(
      "design_variation_image",
      $("#variation-blueprint-input")[0].files[0]
    ); // Handle image upload

    console.log("Variation ID:", variationId); // Debugging line

    if (variationId && variationId !== "") {
      // Update existing variation
      formData.append("action", "save_design_variation");
      formData.append("variation_id", variationId);
    } else {
      // Insert new variation
      formData.append("action", "save_design_variation");
      formData.append("house_design_id", house_design_id);
    }

    $.ajax({
      url: ajaxurl,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.success) {
          alert("Variation saved successfully!");
          $("#add-variation-modal").modal("hide"); // Dismiss the modal
          fetchHouseDesignDetails(house_design_id); // Refresh the variations list
        } else {
          alert("Failed to save variation.");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
      },
    });
  });
  // Save plan
  $("#save-plan-button").on("click", function () {
    const planId = $("#plan-id").val(); // Hidden input for plan ID
    const formData = new FormData();

    formData.append("description", $("#plan-description-input").val());
    formData.append("price", $("#plan-price-input").val());
    formData.append("blueprint_image", $("#plan-blueprint-input")[0].files[0]); // This might be empty if no new file is selected
    formData.append("area", $("#plan-area-input").val());
    formData.append("width", $("#plan-width-input").val());
    formData.append("length", $("#plan-length-input").val());
    formData.append("bedrooms", $("#plan-bedrooms-input").val());
    formData.append("living_rooms", $("#plan-living-rooms-input").val());
    formData.append("bathrooms", $("#plan-bathrooms-input").val());
    formData.append("carport", $("#plan-carport-input").val());

    // Handle the boolean fields (checkboxes)
    formData.append(
      "butlers_pantry",
      $("#plan-butlers-pantry-input").is(":checked") ? 1 : 0
    );
    formData.append(
      "front_master",
      $("#plan-front-master-input").is(":checked") ? 1 : 0
    );
    formData.append(
      "media_room",
      $("#plan-media-room-input").is(":checked") ? 1 : 0
    );
    formData.append(
      "outdoor_living",
      $("#plan-outdoor-living-input").is(":checked") ? 1 : 0
    );
    formData.append(
      "rear_master",
      $("#plan-rear-master-input").is(":checked") ? 1 : 0
    );
    formData.append(
      "dual_living",
      $("#plan-dual-living-input").is(":checked") ? 1 : 0
    );
    formData.append("it_hub", $("#plan-it-hub-input").is(":checked") ? 1 : 0);
    formData.append("study", $("#plan-study-input").is(":checked") ? 1 : 0);
    formData.append(
      "designer",
      $("#plan-designer-input").is(":checked") ? 1 : 0
    );

    if (planId && planId !== "") {
      // Update existing plan
      formData.append("action", "save_house_plan"); // Single action for both save and update
      formData.append("plan_id", planId);
    } else {
      // Insert new plan
      formData.append("action", "save_house_plan"); // Single action for both save and update
      formData.append("house_design_id", house_design_id);
    }

    $.ajax({
      url: ajaxurl,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response.success) {
          alert("Plan saved successfully!");
          $("#add-plan-modal").modal("hide"); // Dismiss the modal
          fetchHouseDesignDetails(house_design_id); // Refresh the plans list
        } else {
          alert("Failed to save plan.");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
      },
    });
  });

  // Delete Variation Function
  function deleteVariation(variationId) {
    $.ajax({
      url: ajaxurl,
      method: "POST",
      data: {
        action: "delete_design_variation",
        design_variation_id: variationId,
      },
      success: function (response) {
        if (response.success) {
          alert("Variation deleted successfully!");
          fetchHouseDesignDetails(house_design_id); // Refresh the variations list
        } else {
          alert("Failed to delete variation.");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
      },
    });
  }

  // Delete Plan Function
  function deletePlan(planId) {
    $.ajax({
      url: ajaxurl,
      method: "POST",
      data: {
        action: "delete_house_plan",
        house_plan_id: planId,
      },
      success: function (response) {
        if (response.success) {
          alert("Plan deleted successfully!");
          fetchHouseDesignDetails(house_design_id); // Refresh the plans list
        } else {
          alert("Failed to delete plan.");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
      },
    });
  }
  $(document).on("click", ".delete-house-design-button", function () {
    const houseDesignId = $(this).data("house-design-id");
    if (confirm("Are you sure you want to delete this house design?")) {
      deleteHouseDesign(houseDesignId);
    }
  });

  function deleteHouseDesign(houseDesignId) {
    $.ajax({
      url: ajaxurl,
      method: "POST",
      data: {
        action: "delete_house_design",
        house_design_id: houseDesignId,
      },
      success: function (response) {
        if (response.success) {
          alert("House Design deleted successfully!");
          loadHouseDesigns(); // Refresh the list on the landing page
        } else {
          alert("Failed to delete house design.");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX request failed:", error);
      },
    });
  }
});
