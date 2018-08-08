<?php
/*
 * This file is part of the Laravel MultiLang package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'texts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
    ];

//    protected $primaryKey = ['key', 'lang', 'scope'];

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
//    protected function setKeysForSaveQuery(Builder $query)
//    {
//        $keys = $this->getKeyName();
//        if(!is_array($keys)){
//            return parent::setKeysForSaveQuery($query);
//        }
//
//        foreach($keys as $keyName){
//            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
//        }
//
//        return $query;
//    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     */
//    protected function getKeyForSaveQuery($keyName = null)
//    {
//        if(is_null($keyName)){
//            $keyName = $this->getKeyName();
//        }
//
//        if (isset($this->original[$keyName])) {
//            return $this->original[$keyName];
//        }
//
//        return $this->getAttribute($keyName);
//    }

//    public function save(array $options = [])
//    {
//        if( ! is_array($this->getKeyName()))
//        {
//            return parent::save($options);
//        }
//
//        // Fire Event for others to hook
//        if($this->fireModelEvent('saving') === false) return false;
//
//        // Prepare query for inserting or updating
//        $query = $this->newQueryWithoutScopes();
//
//        // Perform Update
//        if ($this->exists)
//        {
//            if (count($this->getDirty()) > 0)
//            {
//                // Fire Event for others to hook
//                if ($this->fireModelEvent('updating') === false)
//                {
//                    return false;
//                }
//
//                // Touch the timestamps
//                if ($this->timestamps)
//                {
//                    $this->updateTimestamps();
//                }
//
//                //
//                // START FIX
//                //
//
//
//                // Convert primary key into an array if it's a single value
//                $primary = (count($this->getKeyName()) > 1) ? $this->getKeyName() : [$this->getKeyName()];
//
//                // Fetch the primary key(s) values before any changes
//                $unique = array_intersect_key($this->original, array_flip($primary));
//
//                // Fetch the primary key(s) values after any changes
//                $unique = !empty($unique) ? $unique : array_intersect_key($this->getAttributes(), array_flip($primary));
//
//                // Fetch the element of the array if the array contains only a single element
//                //$unique = (count($unique) <> 1) ? $unique : reset($unique);
//
//                // Apply SQL logic
//                $query->where($unique);
//
//                //
//                // END FIX
//                //
//
//                // Update the records
//                $query->update($this->getDirty());
//
//                // Fire an event for hooking into
//                $this->fireModelEvent('updated', false);
//            }
//        }
//        // Insert
//        else
//        {
//            // Fire an event for hooking into
//            if ($this->fireModelEvent('creating') === false) return false;
//
//            // Touch the timestamps
//            if($this->timestamps)
//            {
//                $this->updateTimestamps();
//            }
//
//            // Retrieve the attributes
//            $attributes = $this->attributes;
//
//            if ($this->incrementing && !is_array($this->getKeyName()))
//            {
//                $this->insertAndSetId($query, $attributes);
//            }
//            else
//            {
//                $query->insert($attributes);
//            }
//
//            // Set exists to true in case someone tries to update it during an event
//            $this->exists = true;
//
//            // Fire an event for hooking into
//            $this->fireModelEvent('created', false);
//        }
//
//        // Fires an event
//        $this->fireModelEvent('saved', false);
//
//        // Sync
//        $this->original = $this->attributes;
//
//        // Touches all relations
//        if (array_get($options, 'touch', true)) $this->touchOwners();
//
//        return true;
//    }
}
