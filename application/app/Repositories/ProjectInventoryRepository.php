<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for notes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class ProjectInventoryRepository
{

    /**
     * The notes repository instance.
     */
    protected $notes;

    /**
     * Inject dependecies
     */
    public function __construct(Note $notes)
    {
        $this->notes = $notes;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @param array $data optional data payload
     * @return object note collection
     */
    public function search($id = '')
    {

        $notes = $this->notes->newQuery();

        // all client fields
        $notes->selectRaw('*');

        //joins
        $notes->leftJoin('users', 'users.id', '=', 'notes.note_creatorid');

        //default where
        $notes->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_note_id')) {
            $notes->where('note_id', request('filter_note_id'));
        }
        if (is_numeric($id)) {
            $notes->where('note_id', $id);
        }

        //resource filtering
        if (request()->filled('noteresource_type') && request()->filled('noteresource_id')) {
            $notes->where('noteresource_type', request('noteresource_type'));
            $notes->where('noteresource_id', request('noteresource_id'));
        }

        //only public or users own private notes
        $notes->where(function ($query) {
            $query->where('note_visibility', 'public');
            $query->orWhere('note_creatorid', auth()->id());
        });

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $notes->where(function ($query) {
                $query->where('note_title', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('note_description', 'LIKE', '%' . request('search_query') . '%');
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('notes', request('orderby'))) {
                $notes->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
                case 'category':
                    $notes->orderBy('category_name', request('sortorder'));
                    break;
            }
        } else {
            //default sorting
            $notes->orderBy('note_id', 'desc');
        }

        //eager load
        $notes->with(['tags']);

        // Get the results and return them.
        return $notes->paginate(config('system.settings_system_pagination_limits'));
    }

    public function get_grn_data($id = '')
    {
        $records = DB::table('grn_tbl')
            ->join('grn_item_mapping', 'grn_tbl.grn_id', '=', 'grn_item_mapping.grn_id')
            ->join('product', 'grn_item_mapping.prd_id', '=', 'product.product_id')
            ->join('purchase_order', 'grn_tbl.po_number', '=', 'purchase_order.purchase_order_id')
            ->where('grn_tbl.whouse', 'p')
            ->where('purchase_order.project_id', $id)
            ->select('grn_tbl.*', 'purchase_order.project_id', 'product.product_name', 'grn_item_mapping.qty_rec', 'grn_item_mapping.qty_need', 'grn_item_mapping.qty_rem', 'grn_item_mapping.prd_rec_date')
            ->get();

        return $records;
    }
    /**
     * Create a new record
     * @return mixed int|bool
     */
    public function create()
    {

        //save new user
        $note = new $this->notes;

        //data
        $note->note_creatorid = auth()->id();
        $note->note_title = request('note_title');
        $note->note_description = request('note_description');
        $note->noteresource_type = request('noteresource_type');
        $note->noteresource_id = request('noteresource_id');

        //save and return id
        if ($note->save()) {
            return $note->note_id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[NoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id note id
     * @return bool
     */
    public function update($id)
    {

        //get the record
        if (!$note = $this->notes->find($id)) {
            Log::error("record could not be found - database error", ['process' => '[MilestoneRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'note_id' => $id ?? '']);
            return false;
        }

        //general
        $note->note_title = request('note_title');
        $note->note_description = request('note_description');

        //save
        if ($note->save()) {
            return $note->note_id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[NoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
}
