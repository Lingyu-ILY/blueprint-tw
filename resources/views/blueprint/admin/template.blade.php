@section("extension.header")
  <img src="{{ $EXTENSION_ICON }}" alt="{{ $EXTENSION_ID }}" style="float:left;width:30px;height:30px;border-radius:3px;margin-right:5px;"/>

  <button class="btn btn-gray-alt pull-right" style="padding: 5px 10px; margin-left: 7px" data-toggle="modal" data-target="#extensionConfigModal">
    <i class="bi bi-gear-fill"></i>
  </button>

  @if($EXTENSION_WEBSITE != "[website]") 
    <a href="{{ $EXTENSION_WEBSITE }}" target="_blank">
      <button class="btn btn-gray-alt pull-right" style="padding: 5px 10px">
        <i class="{{ $EXTENSION_WEBICON }}"></i>
      </button>
    </a>
  @endif

  <h1 ext-title>{{ $EXTENSION_NAME }}<tag mg-left blue>{{ $EXTENSION_VERSION }}</tag></h1>
@endsection

@section("extension.description")
  <p class="ext-description">{{ $EXTENSION_DESCRIPTION }}</p>
@endsection

@section("extension.config")
  <?php
    use Pterodactyl\Models\Egg;
    $eggs = Egg::all();
  ?>
  <div class="modal fade" id="extensionConfigModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="background-color:transparent">
        <form action="/admin/extensions/blueprint/config" method="POST" autocomplete="off">
          <div class="modal-header" style="border-color:transparent; border-radius:7px; margin-bottom: 15px">
            <button type="button" class="close" data-dismiss="modal" aria-label="關閉" style="color:#fff;box-shadow:none"><span aria-hidden="true"><i class="bi bi-x"></i></span></button>
            <h3 class="modal-title">
              <img src="{{ $EXTENSION_ICON }}" alt="logo" height="34" width="34" class="pull-left" style="border-radius:3px;margin-right:10px"/>
              設定 <b>{{ $EXTENSION_NAME }}</b>
            </h3>
          </div>

          <div class="modal-body" style="border-color:transparent; border-radius:7px; margin-bottom: 15px">
            <h4><b>權限</b></h4>
            <p class="text-muted text-left">設定此擴充套件可以或不可以在您的 Pterodactyl 面板上編輯/擴充哪些元素。</p><br>

            <div class="row">
              <div class="col-xs-6">
                <label class="control-label">管理員版面配置</label>
                <select class="form-control" name="{{ $EXTENSION_ID }}_adminlayouts" style="border-radius:6px">
                  <option value="1" @if($blueprint->dbGet('blueprint', 'extensionconfig_'.$EXTENSION_ID.'_adminlayouts') != "0") selected @endif>允許</option>
                  <option value="0" @if($blueprint->dbGet('blueprint', 'extensionconfig_'.$EXTENSION_ID.'_adminlayouts') == "0") selected @endif>封鎖</option>
                </select>
                <p class="text-muted small">允許此擴充套件擴充管理面板版面配置。</p>
              </div>
              <div class="col-xs-6">
                <label class="control-label">儀表板包裝器</label>
                <select class="form-control" name="{{ $EXTENSION_ID }}_dashboardwrapper" style="border-radius:6px">
                  <option value="1" @if($blueprint->dbGet('blueprint', 'extensionconfig_'.$EXTENSION_ID.'_dashboardwrapper') != "0") selected @endif>允許</option>
                  <option value="0" @if($blueprint->dbGet('blueprint', 'extensionconfig_'.$EXTENSION_ID.'_dashboardwrapper') == "0") selected @endif>封鎖</option>
                </select>
                <p class="text-muted small">允許此擴充套件擴充儀表板的 blade 包裝器。</p>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <label class="control-label">路由 Eggs</label>
                <select multiple class="eggOptions form-control" name="{{ $EXTENSION_ID }}_eggs[]">
                  <option value="-1" @if(in_array('-1', json_decode($blueprint->dbGet('blueprint', 'extensionconfig_'.$EXTENSION_ID.'_eggs') ?: '["-1"]'))) selected @endif>在所有 eggs 上顯示</option>
                  @foreach ($eggs as $egg)
                    <option value="{{ $egg->id }}" @if(in_array(strval($egg->id), json_decode($blueprint->dbGet('blueprint', 'extensionconfig_'.$EXTENSION_ID.'_eggs') ?: '["-1"]'))) selected @endif>{{ $egg->name }}</option>
                  @endforeach
                </select>
                <p class="text-muted small">選擇此擴充套件應該可以在哪些 Pterodactyl eggs 上新增頁面。</p>
              </div>
            </div>
          </div>

          <div class="modal-footer" style="border-color:transparent; border-radius:7px">
            {{ csrf_field() }}
            <input type="hidden" name="_identifier" value="{{ $EXTENSION_ID }}">
            <input type="hidden" name="_method" value="PATCH">
            <div class="row">
              <div class="col-sm-10">
                <p class="text-muted small text-left">此設定對話框由 Blueprint 自動產生。更新此擴充套件的設定時，未儲存的變更將會遺失。</p>
              </div>
              <div class="col-sm-2">
                <button type="submit" class="btn btn-primary btn-sm" style="width:100%; margin-top:10px; margin-bottom:10px; border-radius:6px">儲存</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('footer-scripts')
  @parent
  <script>
    $('.eggOptions').select2();
  </script>
  <style>
    .select2-selection {
      border-radius: 6px !important;
    }
    .select2-container--open .select2-selection {
      border-bottom-left-radius: 0px !important;
      border-bottom-right-radius: 0px !important;
    }

    section.content { padding-top: 7px !important; }
    section.content-header > h1 { margin-top: 3px !important; }
    .ext-description { padding-bottom: 10px; }
  </style>
@endsection