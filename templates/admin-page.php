<style>
    .card-fix {
        padding: 0 0 0 !important;
        max-width: none !important;
    }
</style>
<div class="container">
    <div class="" id="landing">
        <div class="row my-5">
            <div class="col-12 row justify-content-around">
                <div class="col-10 col-md-5 mb-2 mb-md-0">
                    <button id="add-location-button" class="btn btn-outline-dark w-100">
                        <i class="fa-solid fa-square-plus"></i> Location
                    </button>
                </div>
                <div class="col-10 col-md-5">
                    <button id="add-house-design-button" class="btn btn-outline-dark w-100">
                        <i class="fa-solid fa-square-plus"></i> House Design
                    </button>
                </div>
            </div>
        </div>

        <div class="row mb-5" id="house-designs">
            <!-- House design items will be dynamically inserted here -->
        </div>
    </div>
</div>

<div class="container-fluid w-100 d-none" id="add-design-ui">
    <button class="btn btn-outline-dark my-3" id="hide-form"><i class="fa-solid fa-delete-left"></i> Back To
        Home</button>
    <div class="row mb-5">
        <div class="col-12">
            <form id="house-design-form">
                <div class="card card-fix">
                    <div class="card-header">House Design Information</div>
                    <div class="card-body row justify-content-start p-3">
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25" id="inputGroup-title">Title</span>
                                <input type="text" class="form-control" id="house-design-title"
                                    aria-label="House Design Title" aria-describedby="inputGroup-title" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25" id="inputGroup-floors">Floors</span>
                                <input type="number" class="form-control" id="house-design-floors"
                                    aria-label="House Design Floors" aria-describedby="inputGroup-floors" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25" id="inputGroup-landing">Landing Page</span>
                                <input type="url" class="form-control" id="house-design-landing-page"
                                    aria-label="Landing Page" aria-describedby="inputGroup-landing" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25" id="inputGroup-quote">Quote Page</span>
                                <input type="url" class="form-control" id="house-design-quote-page"
                                    aria-label="Quote Page" aria-describedby="inputGroup-quote" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <button type="submit" class="btn btn-dark w-100" id="save-house-design">Save</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 mt-5 d-none" id="variations-tab">
            <div class="card card-fix">
                <div class="card-header">Design Variations</div>
                <div class="card-body row p-3">
                    <!-- This is where design variations will be shown for Editing/update, and be generated when you add a new design variation -->

                    <div id="variations" class="d-flex flex-row justify-content-around"></div>

                    <div class="col-12">
                        <div class="card card-fix flex-fill d-flex align-items-center justify-content-center">
                            <button data-bs-toggle="modal" data-bs-target="#add-variation-modal"
                                class="btn btn-outline-dark w-100 h-100"><i class="fa-solid fa-square-plus"></i>
                                Design Variation</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="col-12 mt-5 d-none" id="plans-tab">
            <div class="card card-fix">
                <div class="card-header">Plans</div>
                <div class="card-body row p-3">

                    <!-- Below is an example of existing Plan items/shoud be blank (only button to add)on new house designs -->

                    <!-- Button to add new, will open a modal for adding item -->
                    <div class="col-12 my-5 px-3 d-flex">
                        <div class="card card-fix flex-fill d-flex align-items-center justify-content-center">
                            <div id="plans"></div>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#add-plan-modal"
                                class="btn btn-outline-dark w-100 h-100"><i class="fa-solid fa-square-plus"></i>
                                House Plan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include modals for adding/editing design variations and plans -->
<!-- Adding Design Variation Modal -->
<div class="modal modal-md" id="add-variation-modal" tabindex="-1" aria-labelledby="add-variation-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header text-center">
                <h6 id="add-variation-label">Add Variation</h6>
            </div>
            <div class="modal-body row">
                <div class="col-12 text-start">
                    <img src="https://placehold.co/600x550" class="d-block w-100" alt="Blueprint Image"
                        id="variation-image-preview">
                    <div class="mt-3">
                        <label for="variation-blueprint-input" class="form-label">Upload Blueprint</label>
                        <input class="form-control" type="file" id="variation-blueprint-input">
                    </div>
                </div>
                <div class="input-group mt-3">
                    <span class="input-group-text" id="variation-name-label">Variation Name</span>
                    <input type="text" class="form-control" id="variation-name-input"
                        aria-describedby="variation-name-label" required>
                </div>
                <div class="input-group mt-3 d-none">
                    <input type="text" class="form-control" id="variation-id" aria-describedby="variation-name-label">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark" id="save-variation-button">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Adding Plan Modal -->
<div class="modal modal-xl modal-end" id="add-plan-modal" tabindex="-1" aria-labelledby="add-plan-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header text-center">
                <h6 id="add-plan-label">Add Plan</h6>
            </div>
            <div class="modal-body row">
                <div class="col-12 col-md-6 col-lg-4 text-start">
                    <img src="https://placehold.co/600x550" class="d-block w-100" alt="Blueprint Image"
                        id="plan-image-preview">
                    <div class="mt-3">
                        <label for="plan-blueprint-input" class="form-label">Upload Blueprint</label>
                        <input class="form-control" type="file" id="plan-blueprint-input">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 text-start row">
                    <div class="col-12 mb-0">
                        <label for="plan-price-input" class="form-label">
                            <h6>Input Price</h6>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control text-end" id="plan-price-input">
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="plan-description-input" class="form-label">
                            <h6>Description</h6>
                        </label>
                        <textarea class="form-control" id="plan-description-input" rows="3"
                            placeholder="Enter description"></textarea>
                        <textarea class="form-control d-none" id="plan-id" rows="3"
                            placeholder="Enter description"></textarea>
                    </div>
                    <div class="col-12">
                        <h6 class="text-center">Dimensions</h6>
                        <div class="input-group my-2">
                            <span class="input-group-text w-25">Area</span>
                            <input type="number" class="form-control text-end w-50" id="plan-area-input" step="0.01">
                            <span class="input-group-text w-25">sqm</span>
                        </div>
                        <div class="input-group my-2">
                            <span class="input-group-text w-25">Width</span>
                            <input type="number" class="form-control text-end w-50" id="plan-width-input" step="0.01">
                            <span class="input-group-text w-25">m</span>
                        </div>
                        <div class="input-group my-2">
                            <span class="input-group-text w-25">Length</span>
                            <input type="number" class="form-control text-end w-50" id="plan-length-input" step="0.01">
                            <span class="input-group-text w-25">m</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="col-12 row">
                        <h6 class="text-center">Rooms</h6>
                        <div class="col-6">
                            <div class="input-group m-1">
                                <span class="input-group-text w-50 justify-content-center">
                                    <i class="fa-solid fa-bed"></i>
                                </span>
                                <input id="plan-bedrooms-input" type="number" min="1" max="99"
                                    class="form-control text-center w-50">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group m-1">
                                <span class="input-group-text w-50 justify-content-center">
                                    <i class="fa-solid fa-couch"></i>
                                </span>
                                <input id="plan-living-rooms-input" type="number" min="1" max="99"
                                    class="form-control text-center w-50">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group m-1">
                                <span class="input-group-text w-50 justify-content-center">
                                    <i class="fa-solid fa-bath"></i>
                                </span>
                                <input id="plan-bathrooms-input" type="number" min="1" max="99"
                                    class="form-control text-center w-50">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group m-1">
                                <span class="input-group-text w-50 justify-content-center">
                                    <i class="fa-solid fa-car"></i>
                                </span>
                                <input id="plan-carport-input" type="number" min="1" max="99"
                                    class="form-control text-center w-50">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 row">
                        <h6 class="text-center">Others</h6>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="plan-butlers-pantry-input">
                                </div>
                                <span class="form-control">Butler's Pantry</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="plan-front-master-input">
                                </div>
                                <span class="form-control">Front Master</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="plan-media-room-input">
                                </div>
                                <span class="form-control">Media Room</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="plan-outdoor-living-input">
                                </div>
                                <span class="form-control">Outdoor Living</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="plan-rear-master-input">
                                </div>
                                <span class="form-control">Rear Master</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="plan-dual-living-input">
                                </div>
                                <span class="form-control">Dual Living</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="plan-it-hub-input">
                                </div>
                                <span class="form-control">IT Hub</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="plan-study-input">
                                </div>
                                <span class="form-control">Study</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="plan-designer-input">
                                </div>
                                <span class="form-control">Designer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark" id="save-plan-button">Save</button>
            </div>
        </div>
    </div>
</div>