<form action="{{ $form_route ?? null }}" method="post" class="{{ $context->isAjax() ? 'ajax-form' : '' }}" enctype="multipart/form-data">
    @csrf

    @if(method_exists($data, 'slugTarget'))
        @include ('autocrud::form.slug-master')
    @endif

    @if (method_exists($context, 'prependForm'))
        {!! $context->prependForm($data) !!}
    @endif

    @if ($context->isMultipleTabs())
    <div class="mt-2 autocrud-tabs">
        <ul class="nav nav-tabs" id="autocrud-tab" role="tablist">
            @foreach($context->getTabs() as $tabname)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ Autocrud::slugify($tabname) }}-tab" data-bs-toggle="tab" href="#form-tab-{{ Autocrud::slugify($tabname) }}" role="tab">{{ $tabname }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="tab-content autocrud-content" id="autocrud-tab-content">
        @foreach($context->getTabs() as $tabname)
        <div class="tab-pane card-body fade {{ $loop->first ? 'show active' : '' }}" id="form-tab-{{ Autocrud::slugify($tabname) }}" role="tabpanel">
            <div class="row">
                @php
                    $width = 0;
                @endphp

                @foreach (collect($structure)->where('tab_group', $tabname) as $row)
                    @php
                        $width += $row->formColumn();
                    @endphp

                    @if ($width > 12)
                        @php
                            $width = 0;
                        @endphp
                        </div><div class="row">
                    @endif

                    <div class="col-md-{{ $row->formColumn() }} col-sm-12">
                        @if ($row->view())
                            @if (View::exists($row->view()))
                                @include ($row->view())
                            @else
                                <div class="alert alert-danger">View <strong>{{ $row->view() }} doesnt exists</strong></div>
                            @endif
                        @else
                        <div class="form-group custom-form-group {!! $row->inputType() == 'radio' ? 'radio-box' : '' !!}" data-crud="{{ $row->getField() }}">
                            @if ($row->showLabel())
                            <label class="{{ $row->isMandatory() ? 'required mandatory' : '' }}">{{ $row->name() }}</label>
                            @endif

                            <div>
                                {!! $row->generateInput($data ?? null, $row->translateable() ? $context->isMultiLanguage() : false) !!}
                                @if ($row->translateable() && $context->isMultiLanguage())
                                <div class="autocrud-language-toggle">
                                    @foreach(Autocrud::langs() as $langid => $langlabel)
                                    <span data-lang="{{ $langid }}" class="badge bg-secondary c-pointer {{ $langid == Autocrud::currentLang() ? 'active' : '' }}">{{ $langlabel }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            @if ($row->notes())
                            <div>
                                <small>{{ $row->notes() }}</small>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    @if ($width >= 12)
                        @php
                            $width = 0;
                        @endphp
                        </div><div class="row">
                    @endif

                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    @if (method_exists($context, 'appendForm'))
        {!! $context->appendForm($data) !!}
    @endif

    <div class="save-buttons my-3">
        <button type="submit" class="btn btn-lg btn-primary"><span class="iconify" data-icon="carbon:save"></span> Save Data</button>
    </div>

</form>