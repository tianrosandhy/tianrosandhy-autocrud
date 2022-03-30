<!-- popup content -->
<div class="modal fade" tabindex="-1" role="dialog" id="media-modal" data-backdrop="static">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-xl-3 col-6 mb-2">
                <a href="#" class="btn btn-white bg-white trigger-upload-tab">
                  <span class="iconify" data-icon="uim:upload-alt"></span> <span class="d-none d-sm-inline-block">Upload Image</span>
                </a>
              </div>
              <div class="col-xl-3 d-none d-lg-block d-xl-block"></div>
              <div class="col-xl-3 col-6 text-right">
                <div class="btn-group">
                  <button type="button" class="refresh-button btn btn-info" title="Refresh">
                    <span class="iconify" data-icon="uim:refresh"></span>
                  </button>
                  <button type="button" class="sort-asc btn btn-white" title="Older First">
                    <span class="iconify" data-icon="bi:sort-numeric-down"></span>
                  </button>
                  <button type="button" class="sort-desc btn btn-white desc" title="Older Last">
                    <span class="iconify" data-icon="bi:sort-numeric-down-alt"></span>
                  </button>
                </div>
              </div>
              <div class="col-xl-3 col-6">
                <form action="" class="media-search">
                  <div class="input-group">
                    <input type="search" autocomplete="off" class="form-control" name="keyword" id="media-search-keyword" placeholder="Search Image">
                    <div class="input-group-append">
                      <button type="submit" class="search-button btn btn-secondary" title="Search">
                        <span class="iconify" data-icon="bx:bx-search-alt"></span>
                      </button>
                    </div>						
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="card-body filemanager-content">

          </div>
          <div class="card-body filemanager-upload" style="display:none;">
            <?php
            $max_size = (file_upload_max_size() / 1024 /1024);
            ?>
            <div class="dropzone custom-dropzone dz-clickable mydropzone" data-hash="" upload-limit="{{ intval($max_size) }}" data-target="{{ route('autocrud.media') }}"></div>
            <div>
              <span style="opacity:.5; font-size:.7em; padding:0 .75em;">Maximum Upload Size : {{ number_format($max_size, 2) }} MB</span>
            </div>
            <div class="my-3">
              <a href="#" class="trigger-filemanager btn btn-sm btn-secondary"><< Back to Images Gallery</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script src="{{ asset(config('autocrud.asset_url') . '/media/media.js') }}"></script>
