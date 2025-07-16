# NodeData DataSource for SelectBoxEditors

Select nodes instead of searching via reference editor in Neos CMS.

## Install

```bash
composer require tms/select
```

## Usage

Single-select nodetype configuration:
```yaml
'Your.Package:Type':
  properties:
    yourReference:
      type: reference
      ui:
        inspector:
          editor: 'Neos.Neos/Inspector/Editors/SelectBoxEditor'
          editorOptions:
            dataSourceIdentifier: 'tms-select-nodedata'
            dataSourceAdditionalData:
              nodeTypes: ['Your.Package:TypeThatShouldBeReferenced']
              # Optional parameters
              groupBy: 'Your.Package:GroupType'
              startingPoint: '/start/here/instead/of/rootnode'
              labelPropertyName: 'title'
              setLabelPrefixByNodeContext: true
              previewPropertyName: 'thumbnailImage' # works with Neos 7.2+
```

Multi-select adjustments:
```yaml
'Your.Package:Type':
  properties:
    yourReferences:
      type: references
      ui:
        inspector:
          editorOptions:
            multiple: true # Don't forget to set multiple: true when using type: references
```

### Optional parameters
| Parameter name                | Description                                                                                                                                                                                        |
|-------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `labelPropertyName`           | Choose your specific **text property name** - if not set, the nodes label will be used.                                                                                                            |
| `groupBy`                     | The grouping must be reflected in the node tree. `groupBy` expects the nodetype name of a parent node.                                                                                             |
| `setLabelPrefixByNodeContext` | If set to `true`, labels get prefixed by `[HIDDEN] ...`, `[NOT IN MENUS] ...`, `[NOT LIVE] ...` and `[REMOVED] ...` depending on the node context.                                                 |
| `previewPropertyName`         | Choose your specific **image property name** to display a custom preview icon as mentioned in the [Neos 7.2 release notes](https://www.neos.io/blog/neos-flow-72-released.html#neos-7-1-features). |

### Works with [Sitegeist.Taxonomy](https://github.com/sitegeist/Sitegeist.Taxonomy)

```yaml
'Your.Package:Type':
  properties:
    yourTaxonomyReferences:
      type: references
      ui:
        inspector:
          editor: 'Neos.Neos/Inspector/Editors/SelectBoxEditor'
          editorOptions:
            allowEmpty: true
            multiple: true
            dataSourceIdentifier: 'tms-select-nodedata'
            dataSourceAdditionalData:
              nodeTypes: [ 'Sitegeist.Taxonomy:Taxonomy' ]
              labelPropertyName: title
              startingPoint: '/taxonomies/your-vocabulary'
```

## Acknowledgments
Development sponsored by [tms.development - Online Marketing and Neos CMS Agency](https://www.tms-development.de/)
