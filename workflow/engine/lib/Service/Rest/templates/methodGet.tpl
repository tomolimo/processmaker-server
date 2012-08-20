    /**
     * Implementation for 'GET' method for Rest API
     *
     {% for pk in primaryKeys %}* @param  mixed {{ pk }} Primary key
     {% endfor %}*
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get({{ paramsStr }})
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                {% for column in columns %}$criteria->addSelectColumn({{ classname }}Peer::{{column}});
                {% endfor %}
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = {{ classname }}Peer::retrieveByPK({{ primaryKeys }});
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }
