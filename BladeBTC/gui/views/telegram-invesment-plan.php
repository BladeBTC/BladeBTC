<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/header.php';

use BladeBTC\GUI\Helpers\Form;
use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Session;
use BladeBTC\GUI\Models\AccountModel;
use BladeBTC\GUI\Models\GroupModel;

?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="module">Investment Plans (Bot)</h1>
            <ol class="breadcrumb">
                <li><a href="<?php echo Path::module(); ?>/telegram-invesment-plan.php"><i class="fa fa-dashboard"></i>Investment Plans (Bot)</a>
                </li>

            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add an Investment Plan</h3>
                        </div>
                     
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <form action="<?php echo Path::module() ?>/telegram-investment-plan.php"
                                                  method="post">
                                                <input type="hidden" name="DBLP"
                                                       value="<?php echo Session::setFormId('mng-account'); ?>">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Minimum Invest</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control" name="minimum_invest" type="text"
                                                                   value="<?php Form::get('minimum_invest') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Minimum Reinvest</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control pointer" name="minimum_reinvest"
                                                                   type="text"
                                                                   value="<?php Form::get('minimum_reinvest') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Minimum Payout</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control" name="minimum_payout" type="text"
                                                                   value="<?php Form::get('minimum_payout') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Base Rate</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control pointer" name="base_rate"
                                                                   type="text"
                                                                   value="<?php Form::get('base_rate') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Contract Days</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control" name="contract_day" type="text"
                                                                   value="<?php Form::get('contract_day') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Commission Rate</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control pointer" name="commission_rate"
                                                                   type="text"
                                                                   value="<?php Form::get('commission_rate') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Timer Time Hour</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control" name="timer_time_hour" type="text"
                                                                   value="<?php Form::get('timer_time_hour') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Required Confirmations</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control pointer" name="required_confirmations"
                                                                   type="text"
                                                                   value="<?php Form::get('required_confirmations') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Interest On Reinvest</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control" name="interest_on_reinvest" type="text"
                                                                   value="<?php Form::get('interest_on_reinvest') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                                        <label>Withdraw Fee</label>
                                                        <div class="input-group"><span class="input-group-addon"><i
                                                                        class="fa fa-user"></i></span>
                                                            <input class="form-control pointer" name="withdraw_fee"
                                                                   type="text"
                                                                   value="<?php Form::get('withdraw_fee') ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <input class="btn bg-orange" type="submit" name="save-account"
                                                       value="Save Investment Plan">
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Investment Plan List</h3>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <!-- Tableau -->
                                    <div class="table-responsive">
                                        <table id="mng_user" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Username</th>
                                                <th>Group</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            $accounts = AccountModel::getAll();
                                            while ($account = $accounts->fetchObject()) {

                                                $rm = '?action=delete_account&id=' . $account->id . '&token=' . $token;
                                                $vim = '?action=edit_account&id=' . $account->id . '&token=' . $token;
                                                $rst = '?action=reset_pwd&id=' . $account->id . '&token=' . $token;
                                                $unl = '?action=unlock&id=' . $account->id . '&token=' . $token;

                                                echo '<tr>
                                                        <td>' . $account->first_name . '</td>
                                                        <td>' . $account->last_name . '</td>
                                                        <td>' . $account->username . '</td>
                                                        <td>' . GroupModel::getNameById($account->account_group) . '</td>
                                                        <td>' . $account->email . '</td>
                                                        <td>
                                                            <a  class="btn btn-success btn-xs" 
                                                                title="Edit account" 
                                                                data-toggle="tooltip"
                                                                href="' . $vim . '">
                                                                <i class="fa fa-pencil fa-fw"></i>
                                                            </a>
                                                            
                                                               <a  class="btn btn-primary btn-xs" 
                                                                title="Reset password to: bladebtc" 
                                                                data-toggle="tooltip"
                                                                onclick="return iconfirm(\'Attention!\',\'Are you sure you want to reset the password?\',this.href)" 
                                                                href="' . $rst . '">
                                                                <i class="fa fa-key fa-fw"></i>
                                                            </a>
                                                            
                                                            <a  class="btn btn-warning btn-xs" 
                                                                title="Unlock account" 
                                                                data-toggle="tooltip"
                                                                onclick="return iconfirm(\'Attention!\',\'Are you sure you want to unlock the account?\',this.href)" 
                                                                href="' . $unl . '">
                                                                <i class="fa fa-unlock-alt fa-fw"></i>
                                                            </a>
                                                            <a  class="btn btn-danger btn-xs" 
                                                                title="Delete account" 
                                                                data-toggle="tooltip"
                                                                onclick="return iconfirm(\'Attention!\',\'Are you sure you want to delete this user?\',this.href)" 
                                                                href="' . $rm . '">
                                                                <i class="fa fa-trash-o fa-fw"></i>
                                                            </a>
                                                            
                                                         
                                                        </td>
                                                        </tr>';
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/footer.php';