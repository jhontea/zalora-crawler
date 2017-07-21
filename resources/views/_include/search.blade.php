            <!-- Search Box -->
            <div class="row">
                <div class="col-xs-8 col-xs-offset-2">
                {!! Form::open(['route' => 'search']) !!}
                    <div class="input-group">
                        <input type="text" class="form-control" name="searchWord" placeholder="Search">
                        <span class="input-group-btn">
                            <button class="btn btn-black" type="submit"><span class="glyphicon glyphicon-search white"></span></button>
                        </span>
                    </div>
                {!! Form::close() !!}
                </div>
            </div>
            <!-- End of Search Box-->
