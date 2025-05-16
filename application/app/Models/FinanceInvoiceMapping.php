<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceInvoiceMapping extends Model {

    /**
     * @primaryKey string - primry key column.
     * @dateFormat string - date storage format
     * @guarded string - allow mass assignment except specified
     * @CREATED_AT string - creation date column
     * @UPDATED_AT string - updated date column
     */
    protected $table = 'finance_invoice_description_mapping';
    protected $primaryKey = 'finance_invoice_description_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['finance_invoice_description_id '];
    const CREATED_AT = 'created_datetime';
    const UPDATED_AT = 'modified_datetime';

    /**
     * relatioship business rules:
     *         - the Creator (user) can have many Invoices
     *         - the Invoice belongs to one Creator (user)
     */
    public function creator() {
        return $this->belongsTo('App\Models\User', 'bill_creatorid', 'id');
    }

    /**
     * relatioship business rules:
     *         - the Invoice belongs to one Client
     */
    public function client() {
        return $this->belongsTo('App\Models\Client', 'bill_clientid', 'client_id');
    }

    /**
     * relatioship business rules:
     *         - the Invoice belongs to one Project
     */
    public function project() {
        return $this->belongsTo('App\Models\Project', 'bill_projectid', 'project_id');
    }

    /**
     * relatioship business rules:
     *         - the Category can have many Invoices
     *         - the Invoice belongs to one Category
     */
    public function category() {
        return $this->belongsTo('App\Models\Category', 'bill_categoryid', 'category_id');
    }

    /**
     * relatioship business rules:
     *         - the Invoice can have many Lineitems
     *         - the Lineitem belongs to one Invoice
     *         - other Lineitems can belong to other tables
     */
    public function lineitems() {
        return $this->morphMany('App\Models\Lineitem', 'lineitemresource');
    }

    /**
     * relatioship business rules:
     *         - the Invoice can have many Payments
     *         - the Payment belongs to one Invoice
     */
    public function payments() {
        return $this->hasMany('App\Models\Payment', 'payment_invoiceid', 'bill_invoiceid');
    }

    /**
     * relatioship business rules:
     *         - the Invoice can have many Tags
     *         - the Tags belongs to one Invoice
     *         - other tags can belong to other tables
     */
    public function tags() {
        return $this->morphMany('App\Models\Tag', 'tagresource');
    }

    /**
     * display format for invoice id - adding leading zeros & with any set prefix
     * e.g. INV-000001
     */
    public function getFormattedBillInvoiceidAttribute() {
        return runtimeInvoiceIdFormat($this->bill_invoiceid);
    }

    /**
     */
    public function taxes() {
        return $this->morphMany('App\Models\Tax', 'taxresource');
    }

}
